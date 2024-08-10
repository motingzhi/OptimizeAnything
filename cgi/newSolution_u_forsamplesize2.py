#!/usr/bin/python3 
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

# # Define the log file path
# log_file_folder = "../python_log"
# log_file_path = os.path.join(log_file_folder, "newSolution_u.log")

# # Create the log file directory if it doesn't exist
# os.makedirs(log_file_folder, exist_ok=True)

# # Redirect stdout and stderr to the log file
# # sys.stdout = open(log_file_path, "a")
# sys.stderr = open(log_file_path, "a")

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
# 在objectivenames的length里循环######自己加的

objective_bounds = torch.zeros(2,len(objectiveNames))
for i in range (len(objectiveNames)):
    objective_bounds[0][i] = float(objectiveBounds[2*i])
    objective_bounds[1][i] = float(objectiveBounds[2*i + 1])
######自己加的
    


obj = [float(x) for x in objective_Measurements]

for i in range(len(currentSolutions)):
    currentSolutions[i] = float(currentSolutions[i])

nested_list = [currentSolutions[i:i + num_parameters] for i in range(0, len(currentSolutions), num_parameters)]


if (len(objectivesInput) != 0):
    objectivesInputPlaceholder = []
    for i in range(int(len(objectivesInput)/len(objectiveNames))):
            sub_list = [float(x) for x in objectivesInput[len(objectiveNames)*i:len(objectiveNames)*i+len(objectiveNames)]]
            objectivesInputPlaceholder.append(sub_list)
        # objectivesInputPlaceholder.append([float(objectivesInput[2*i]), float(objectivesInput[2*i+1]),float(objectivesInput[2*i+2])])
    objectivesInput = objectivesInputPlaceholder
objectivesInput.append(obj)


test=len(savedObjectives)/len(objectiveNames)

# savedSolutions.append(currentSolutions[len(savedObjectives) - len(parameterNames) + 1 : len(savedObjectives) + 1 ])#这是错的，因为1应该调整为和num(parameter)相关的 (num(parameter)-1)。
savedSolutions.append(nested_list[(len(savedObjectives)/len(objectiveNames))-1])
savedObjectives.append(obj)
solutionNameList.append(solutionName)
# savedSolutions.append(currentSolutions[len(savedObjectives) - 1  : len(savedObjectives) + (len(parameterNames)-1) ])

#“：”后面是切片的结束位置，切片的结束位置不包括在切片得出的结果内。

# savedSolutions.append([999,66])



message = "Necessary objects imported."
success = True
tester = 1
xx = 57
reply2 = {}

bad_solutions.append(currentSolutions[-1*num_parameters:])

reply2['solution'] = currentSolutions

# reply['newSolution'] = newSolution[0]
reply2['objectives'] = objectivesInput
reply2['bad_solutions'] = bad_solutions
reply2['saved_solutions'] = savedSolutions
reply2['saved_objectives'] = savedObjectives
reply2['solutionNameList'] = solutionNameList
reply2['test'] = xx
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
