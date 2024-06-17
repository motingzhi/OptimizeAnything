#!/usr/local/bin/python3
#/usr/bin/python3 
import sys 

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
from botorch.utils.sampling import draw_sobol_samples # newSolution.py


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
log_file_path = os.path.join(log_file_folder, "newSolution2.log")

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

# newSolution = (formData['new-solution'].value).split(',')
# nextEvaluation = (formData['next-evaluation'].value).split(',')
# refineSolution = (formData['refine-solution'].value).split(',')

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

n_sample =  2*(len(parameterNames)+1)
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

# 在objectivenames的length里循环######自己加的

objective_bounds = torch.zeros(2,len(objectiveNames))
for i in range (len(objectiveNames)):
    objective_bounds[0][i] = float(objectiveBounds[2*i])
    objective_bounds[1][i] = float(objectiveBounds[2*i + 1])
######自己加的
    
def unnormalise_parameters(x_tensor, x_bounds = parameter_bounds):
    x_actual = torch.zeros(n_sample, num_parameters)#生成n_sample个train_x_actual和
    for x in range(n_sample):
        for i in range(num_parameters):
            x_actual[x][i] = x_tensor[x][i]*(x_bounds[1][i] - x_bounds[0][i]) + x_bounds[0][i]
    return x_actual

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
    # print(proposed_solution[0][1])
    # print(bad_solutions[i][0])
    for y in range(len(parameterNames)):
        if (proposed_solution[0][y]) < float(bad_solutions[i][0])+parameter_bounds_range[0]*0.05 and proposed_solution[0][y] > float(bad_solutions[i][0])-parameter_bounds_range[0]*0.05:
            if y == len(parameterNames)-1:
                return False
            else:
                return True
        else:
            break   
    # if (proposed_solution[0][0] < float(bad_solutions[i][0])+parameter_bounds_range[0]*0.05 
    #     and proposed_solution[0][0] > float(bad_solutions[i][0])-parameter_bounds_range[0]*0.05 
    #     and proposed_solution[0][1] < float(bad_solutions[i][1])+parameter_bounds_range[1]*0.05 
    #     and proposed_solution[0][1] > float(bad_solutions[i][1])-parameter_bounds_range[1]*0.05):
    #   return False
    # if (proposed_solution[0][0] < float(bad_solutions[i][0])*1.05 and proposed_solution[0][0] > float(bad_solutions[i][0])*0.95 and proposed_solution[0][1] < float(bad_solutions[i][1])*1.05 and proposed_solution[0][1] > float(bad_solutions[i][1])*0.95):
    #   return False
  return True


def generate_initial_data(n_samples=n_sample):
    # generate training data
    train_x = draw_sobol_samples(
        bounds=parameter_bounds_normalised, n=1, q=n_samples, seed=torch.randint(1000000, (1,)).item()
    ).squeeze(0)
    train_x = train_x.type(torch.DoubleTensor)
    train_x_actual = torch.round(unnormalise_parameters(train_x))
    # print("Initial solution: ", train_x_actual)
    if (badSolutions != []):
        while (checkForbiddenRegions(bad_solutions, train_x_actual) == False):
            # print("Proposed solution in forbidden region")
            train_x = draw_sobol_samples(
                # bounds=problem_bounds, n=1, q=n_samples, seed=torch.randint(1000000, (1,)).item()
                bounds=parameter_bounds_normalised, n=1, q=n_samples, seed=torch.randint(1000000, (1,)).item() #Might be the correct version
            ).squeeze(0)
            train_x = train_x.type(torch.DoubleTensor)
            train_x_actual = unnormalise_parameters(train_x)
    return train_x, train_x_actual

obj = [float(x) for x in objective_Measurements]

for i in range(len(currentSolutions)):
    currentSolutions[i] = float(currentSolutions[i])

if (len(objectivesInput) != 0):
    objectivesInputPlaceholder = []
    for i in range(int(len(objectivesInput)/len(objectiveNames))):
            sub_list = [float(x) for x in objectivesInput[len(objectiveNames)*i:len(objectiveNames)*i+len(objectiveNames)]]
            objectivesInputPlaceholder.append(sub_list)
        # objectivesInputPlaceholder.append([float(objectivesInput[2*i]), float(objectivesInput[2*i+1]),float(objectivesInput[2*i+2])])
    objectivesInput = objectivesInputPlaceholder
objectivesInput.append(obj)
savedObjectives.append(obj)
solutionNameList.append(solutionName)

parametersPlaceholder = []
for i in range(int(len(currentSolutions)/num_parameters)):
    parametersPlaceholder.append(currentSolutions[i*num_parameters:i*num_parameters+num_parameters])
train_x_actual = torch.tensor(parametersPlaceholder, dtype=torch.float64)
savedSolutions.append(train_x_actual.tolist()[-1])


message = "Necessary objects imported."
success = True
tester = 1
xx = 57
reply2 = {}

bad_solutions.append(currentSolutions[-1*num_parameters:])
currentSolutions = []
train_x, train_x_actual = generate_initial_data()
currentSolutions.append(train_x_actual.tolist())#和sample的数目保持一致 用户不能跳过
reply2['solution'] = currentSolutions
# reply['newSolution'] = newSolution[0]
reply2['objectives'] = objectivesInput
reply2['bad_solutions'] = bad_solutions
reply2['saved_solutions'] = savedSolutions
reply2['saved_objectives'] = savedObjectives
reply2['test'] = xx
reply2['train_x_actual'] = train_x_actual.tolist()
tester = 9

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


