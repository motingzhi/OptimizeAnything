#/usr/bin/python3 
import sys
# import logging
import os
import json
import cgi
import numpy as np

import torch
from botorch.utils.multi_objective.hypervolume import Hypervolume
from botorch.models.gp_regression import SingleTaskGP
from botorch.models.transforms.outcome import Standardize
from gpytorch.mlls.exact_marginal_log_likelihood import ExactMarginalLogLikelihood
from botorch.utils.multi_objective.pareto import is_non_dominated
from botorch import fit_gpytorch_model
from botorch.sampling.normal import SobolQMCNormalSampler
from botorch.utils.multi_objective.box_decompositions.non_dominated import NondominatedPartitioning
from botorch.acquisition.multi_objective.monte_carlo import qExpectedHypervolumeImprovement
from botorch.optim.optimize import optimize_acqf
from botorch.utils.transforms import unnormalize

# logging.basicConfig(filename='next-evaluation.log', level=logging.DEBUG)  # 将日志级别设置为 DEBUG，并输出到 example.log 文件中


BATCH_SIZE = 1 # Number of design parameter points to query at next iteration
NUM_RESTARTS = 10 # Used for the acquisition function number of restarts in optimization
RAW_SAMPLES = 1024 # Initial restart location candidates
N_ITERATIONS = 35 # Number of optimization iterations
MC_SAMPLES = 512 # Number of samples to approximate acquisition function
N_INITIAL = 5
SEED = 2 




message = "Necessary objects imported."
success = True
tester = 1
xx = 57
reply2 = {}

# Define the log file path
log_file_folder = "../python_log"
log_file_path = os.path.join(log_file_folder, "Next-evaluation.log")

# Create the log file directory if it doesn't exist
os.makedirs(log_file_folder, exist_ok=True)

# Redirect stdout and stderr to the log file
# sys.stdout = open(log_file_path, "a")
sys.stderr = open(log_file_path, "a")

# Read provided formData
formData = cgi.FieldStorage()


parameterNames = (formData['parameter-names'].value).split(',')
parameterBounds = (formData['parameter-bounds'].value).split(',')
objectiveNames = (formData['objective-names'].value).split(',')
objectiveBounds = (formData['objective-bounds'].value).split(',')
objectiveMinMax = (formData['objective-min-max'].value).split(',')

try:
    goodSolutions = (formData['good-solutions'].value).split(',')
except:
    pass
try:
    badSolutions = (formData['bad-solutions'].value).split(',')
except:
    badSolutions = []
try:
    currentSolutions = (formData['current-solutions'].value).split(',')
except:
    currentSolutions = []
try:
    savedSolutions = (formData['saved-solutions'].value).split(',')
except:
    savedSolutions = []
try:
    savedObjectives = (formData['saved-objectives'].value).split(',')
except:
    savedObjectives = []
try:
    objectivesInput = (formData['objectives-input'].value).split(',')
except:
    objectivesInput = []

try:
    solutionNameList = (formData['solution-name-list'].value).split(',')
except:
    solutionNameList = []


try:
    solutionName = (formData['solution-name'].value).split(',')
except:
    pass
try: 
    objective_Measurements = (formData['objective-measurements'].value).split(',')

    # objectiveMeasurements = float((formData['objective-measurements'].value))

    # obj_measurements = float((formData['objective-measurements'].value))
except:
    pass

num_parameters = len(parameterNames)
parameter_bounds = torch.zeros(2, num_parameters)
parameter_bounds_normalised = torch.zeros(2, num_parameters)#

parameter_bounds_range = []
for i in range(num_parameters):
    parameter_bounds[0][i] = float(parameterBounds[2*i])
    parameter_bounds[1][i] = float(parameterBounds[2*i + 1])
    parameter_bounds_normalised[0][i] = float(0)
    parameter_bounds_normalised[1][i] = float(1)
    parameter_bounds_range.append(float(parameterBounds[2*i + 1]) - float(parameterBounds[2*i]))

bad_solutions = []
for i in range(int(len(badSolutions)/num_parameters)):
    bad_solutions.append(badSolutions[i*num_parameters:i*num_parameters+num_parameters])

obj_ref_point = torch.tensor([-1.]*len(objectiveNames))

objective_bounds = torch.zeros(2,len(objectiveNames))
for i in range (len(objectiveNames)):
    objective_bounds[0][i] = float(objectiveBounds[2*i])
    objective_bounds[1][i] = float(objectiveBounds[2*i + 1])
######自己加的

def unnormalise_parameters(x_tensor, x_bounds = parameter_bounds):
    x_actual = torch.zeros(1, num_parameters)
    for i in range(num_parameters):
        x_actual[0][i] = x_tensor[0][i]*(x_bounds[1][i] - x_bounds[0][i]) + x_bounds[0][i]
    return x_actual


def normalise_parameters(x_tensor, x_bounds = parameter_bounds):
    x_norm = torch.zeros(x_tensor.size(), dtype=torch.float64)
    for j in range(x_tensor.size()[0]): # TESTING INDEX ERROR
        for i in range(x_tensor.size()[1]):
            x_norm[j][i] = (x_tensor[j][i] - x_bounds[0][i])/(x_bounds[1][i] - x_bounds[0][i]) 
    return x_norm



def normalise_objectives(obj_tensor_actual):
    objectives_min_max = objectiveMinMax
    obj_tensor_norm = torch.zeros(obj_tensor_actual.size(), dtype=torch.float64)
    for j in range(obj_tensor_actual.size()[0]):
        for i in range (obj_tensor_actual.size()[1]):
            if (objectives_min_max[i] == "minimise"): # MINIMISE (SMALLER VALUES CLOSER TO 1)
                obj_tensor_norm[j][i] = -2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) + 1
            elif (objectives_min_max[i] == "maximise"): # MAXIMISE (LARGER VALUES CLOSER TO -1)
                obj_tensor_norm[j][i] =  2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) - 1
    return obj_tensor_norm


def checkForbiddenRegions(bad_solutions, proposed_solution): # +/- 5% of bad solution parameters  
  for i in range(int(len(badSolutions)/num_parameters)):
    for y in range(len(parameterNames)):
        if (proposed_solution[0][y]) < float(bad_solutions[i][0])+parameter_bounds_range[0]*0.05 and proposed_solution[0][y] > float(bad_solutions[i][0])-parameter_bounds_range[0]*0.05:
            return True
            break   
  return False


def initialize_model(train_x, train_obj):
    # define models for objective and constraint
    model = SingleTaskGP(train_x, train_obj, outcome_transform=Standardize(m=train_obj.shape[-1]))
    mll = ExactMarginalLogLikelihood(model.likelihood, model)
    return mll, model

# tester9 = True
def optimize_qehvi(model, train_obj, sampler, parameter_bounds=parameter_bounds):
    """Optimizes the qEHVI acquisition function, and returns a new candidate and observation."""
    # partition non-dominated space into disjoint rectangles
    partitioning = NondominatedPartitioning(ref_point=obj_ref_point, Y=train_obj)#和Pareto前沿有关
    acq_func = qExpectedHypervolumeImprovement(
        model=model,
        ref_point=obj_ref_point.tolist(),  # use known reference point
        partitioning=partitioning,
        sampler=sampler,
    )
    # optimize
    candidates, _ = optimize_acqf(
        acq_function=acq_func,
        bounds=parameter_bounds_normalised,
        q=BATCH_SIZE,
        num_restarts=NUM_RESTARTS,
        raw_samples=RAW_SAMPLES,  # used for intialization heuristic
        options={"batch_limit": 5, "maxiter": 200, "nonnegative": True},
        sequential=True,
    )
    # observe new values
    new_x =  unnormalize(candidates.detach(), bounds=parameter_bounds_normalised)
    new_x_actual = unnormalise_parameters(new_x, parameter_bounds)

    # if (badSolutions != []):
    #     while (checkForbiddenRegions(bad_solutions, new_x_actual) == False):
    #         new_x_actual = torch.tensor([[np.random.randint(parameter_bounds[0][0], parameter_bounds[1][0]), np.random.randint(parameter_bounds[0][1], parameter_bounds[1][1])]])
    #         new_x = normalise_objectives(new_x_actual)
    # while (checkRepeated(bad_solutions, new_x_actual) == False):
    #     new_x_actual = torch.tensor([[np.random.randint(parameter_bounds[0][0], parameter_bounds[1][0]), np.random.randint(parameter_bounds[0][1], parameter_bounds[1][1])]])
    #     new_x = normalise_objectives(new_x_actual)
    return new_x, new_x_actual



obj = [float(x) for x in objective_Measurements]
# logging.debug(obj)

for i in range(len(currentSolutions)):
    currentSolutions[i] = float(currentSolutions[i])

# train_obj_actual = torch.tensor([[obj1, obj2]], dtype=torch.float64)
# if (len(objectivesInput) != 0):
#     objectivesInputPlaceholder = []
#     for i in range(int(len(objectivesInput)/len(objectiveNames))):
#             sub_list = [float(x) for x in objectivesInput[len(objectiveNames)*i:len(objectiveNames)*i+len(objectiveNames)]]
#             objectivesInputPlaceholder.append(sub_list)
#         # objectivesInputPlaceholder.append([float(objectivesInput[2*i]), float(objectivesInput[2*i+1]),float(objectivesInput[2*i+2])])
#     objectivesInput = objectivesInputPlaceholder
# objectivesInput.append(obj)
# savedObjectives.append(obj)

# solutionNameList.append(solutionName)


train_obj_actual = torch.tensor(objectivesInput, dtype=torch.float64)
train_obj = normalise_objectives(train_obj_actual)

parametersPlaceholder = []
for i in range(int(len(currentSolutions)/num_parameters)):
    parametersPlaceholder.append(currentSolutions[i*num_parameters:i*num_parameters+num_parameters])
train_x_actual = torch.tensor(parametersPlaceholder, dtype=torch.float64)
savedSolutions.append(train_x_actual.tolist()[-1])
# train_x_actual = torch.zeros(1,num_parameters, dtype=torch.float64)
# for i in range(1, num_parameters+1):
#     train_x_actual[0][-1*i] = float(currentSolutions[-1*i])
train_x = normalise_parameters(train_x_actual)

torch.manual_seed(SEED)

hv = Hypervolume(ref_point=obj_ref_point)
# Hypervolumes
hvs_qehvi = []
# Initialize GP models
mll, model = initialize_model(train_x, train_obj)
# Compute Pareto front and hypervolume
pareto_mask = is_non_dominated(train_obj)
pareto_y = train_obj[pareto_mask]
volume = hv.compute(pareto_y)
hvs_qehvi.append(volume)

# Fit Models
fit_gpytorch_model(mll)
# Define qEI acquisition modules using QMC sampler
qehvi_sampler = SobolQMCNormalSampler(sample_shape=torch.Size([MC_SAMPLES]))
# qehvi_sampler = SobolQMCNormalSampler(num_samples=MC_SAMPLES) #original
# Optimize acquisition functions and get new observations
new_x, new_x_actual = optimize_qehvi(model, train_obj, qehvi_sampler)
new_x_actual = torch.round(new_x_actual)

# Update training points
train_x = torch.cat([train_x, new_x])
train_x_actual = torch.cat([train_x_actual, new_x_actual])
# train_obj = torch.cat([train_obj, new_obj])
# train_obj_actual = torch.cat([train_obj_actual, new_obj_actual])



currentSolutions.append(train_x_actual.tolist()[-1])
#savedSolutions.append(train_x_actual.tolist()[-1])
reply2['solution'] = currentSolutions
reply2['objectives'] = objectivesInput
reply2['solution_normalised'] = train_x.tolist()
reply2['bad_solutions'] = bad_solutions
reply2['saved_solutions'] = savedSolutions
reply2['saved_objectives'] = savedObjectives
reply2['solutionNameList'] = solutionNameList

tester = 3

reply = {}
reply['success'] = success
reply['message'] = message
reply['tester'] = tester
reply['parameterNames']= parameterNames
reply.update(reply2)

sys.stdout.write("Content-Type: application/json")

sys.stdout.write("\n")
sys.stdout.write("\n")

sys.stdout.write(json.dumps(reply,indent=1))
sys.stdout.write("\n")

# Close the log file
sys.stdout.close()
sys.stderr.close() 
