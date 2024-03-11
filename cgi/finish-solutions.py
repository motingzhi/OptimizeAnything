#!/usr/bin/python3

# /Users/fengyu.li/anaconda3/bin/python3
# /usr/bin/env python3
import sys
#sys.path.append("/usr/local/lib/python3.11/site-packages")
#sys.path.append("/Users/fengyu.li/anaconda3/lib/python3.10/site-packages")
import os
import json
import cgi
import sqlite3
import requests
# import os

####From import_all

import numpy as np
# from matplotlib import pyplot as plt
import torch
import os
from botorch.utils.multi_objective.pareto import is_non_dominated

# from botorch.utils.transforms import unnormalize
# from botorch.utils.sampling import draw_sobol_samples
# from botorch.optim.optimize import optimize_acqf, optimize_acqf_list
# from botorch.utils.multi_objective.scalarization import get_chebyshev_scalarization
# from botorch.utils.sampling import sample_simplex
# from botorch import fit_gpytorch_model
#from botorch.sampling.samplers import SobolQMCNormalSampler

# tkwargs = {
#     "dtype": torch.double,
#     "device": torch.device("cuda" if torch.cuda.is_available() else "cpu"),
# }

# Global Variables

# BATCH_SIZE = 1 # Number of design parameter points to query at next iteration
# NUM_RESTARTS = 10 # Used for the acquisition function number of restarts in optimization
# RAW_SAMPLES = 1024 # Initial restart location candidates
# N_ITERATIONS = 35 # Number of optimization iterations
# MC_SAMPLES = 512 # Number of samples to approximate acquisition function
# N_INITIAL = 5
# SEED = 2 # Seed to initialize the initial samples obtained
# # success = False
# ##### Import_all ends

# print("Content-Type: text/html; charset=utf-8\n")
# print("ąęśłłłóąś UTF answer")
# Initialize the basic reply message
reply = {}
message = "Necessary objects imported."
success = True

# Define the log file path
log_file_folder = "../python_log"
log_file_path = os.path.join(log_file_folder, "finish_solutions2.log")

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
savedSolutions = (formData['saved-solutions'].value).split(',')
savedObjectives = (formData['saved-objectives'].value).split(',')
# objectivesInput = (formData['objectives-input'].value).split(',')

num_parameters = len(parameterNames)

parameter_bounds = torch.zeros(2, num_parameters)
parameter_bounds_normalised = torch.zeros(2, num_parameters)
for i in range(num_parameters):
    parameter_bounds[0][i] = float(parameterBounds[2*i])
    parameter_bounds[1][i] = float(parameterBounds[2*i + 1])
    parameter_bounds_normalised[0][i] = float(0)
    parameter_bounds_normalised[1][i] = float(1)
    
objective_bounds = torch.zeros(2,len(objectiveNames))
for i in range (len(objectiveNames)):
    objective_bounds[0][i] = float(objectiveBounds[2*i])
    objective_bounds[1][i] = float(objectiveBounds[2*i + 1])

# def normalise_objectives(obj_tensor_actual):
#     objectives_min_max = objectiveMinMax
#     obj_tensor_norm = torch.zeros(obj_tensor_actual.size(), dtype=torch.float64)
#     for j in range(obj_tensor_actual.size()[0]):
#         for i in range (obj_tensor_actual.size()[1]):
#             if (objectives_min_max[i] == "minimise"): # MINIMISE (SMALLER VALUES CLOSER TO 1)
#                 obj_tensor_norm[j][i] = -2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) + 1
#             elif (objectives_min_max[i] == "maximise"): # MAXIMISE (LARGER VALUES CLOSER TO -1)
#                 obj_tensor_norm[j][i] =  2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) - 1
#     return obj_tensor_norm


def Maximise_all_objectives(obj_tensor_actual):
    objectives_min_max = objectiveMinMax
    obj_tensor_norm = torch.zeros(obj_tensor_actual.size(), dtype=torch.float64)#生成空位
    for j in range(obj_tensor_actual.size()[0]):
        for i in range (obj_tensor_actual.size()[1]):
            if (objectives_min_max[i] == "minimise"): # MINIMISE (SMALLER VALUES CLOSER TO 1)
                obj_tensor_norm[j][i] = -1*obj_tensor_actual[j][i]
            elif (objectives_min_max[i] == "maximise"): # MAXIMISE (LARGER VALUES CLOSER TO -1)
                obj_tensor_norm[j][i] = 1*obj_tensor_actual[j][i]

    return obj_tensor_norm

# objectivesInputPlaceholder = []
# for i in range(int(len(savedObjectives)/2)):
#     objectivesInputPlaceholder.append([float(savedObjectives[2*i]), float(savedObjectives[2*i+1])])

objectivesInputPlaceholder = []
for i in range(int(len(savedObjectives)/len(objectiveNames))):
    sub_list = [float(x) for x in savedObjectives[len(objectiveNames)*i:len(objectiveNames)*i+len(objectiveNames)]]
    objectivesInputPlaceholder.append(sub_list)
            # objectivesInputPlaceholder.append([float(objectivesInput[2*i]), float(objectivesInput[2*i+1]),float(objectivesInput[2*i+2])])

savedObjectives = objectivesInputPlaceholder
train_obj_actual = torch.tensor(savedObjectives, dtype=torch.float64)
train_obj_max = Maximise_all_objectives(train_obj_actual)
pareto_mask = is_non_dominated(train_obj_max)

BestSolutionIndex = []

for i in range(pareto_mask.size()[0]):
    if pareto_mask[i] ==  True: 
        BestSolutionIndex.append(i)

# # best_obj_normalised = [-100] * len(objectiveNames)
# # best_obj_1_normalised = -100
# # best_obj_2_normalised = -100
# # best_obj_balance_normalised = -100

# objectives_list_normalised = train_obj.tolist()

# obj_normalised = [[] for _ in range(len(objectiveNames))]

# # obj_1_normalised = []
# # obj_2_normalised = []

# obj_balance_normalised = []

# for i in range(int(len(objectives_list_normalised))):
#     sum = 0
#     for l in range(len(objectiveNames)):
#         obj_normalised[l].append(objectives_list_normalised[i][l])
#         sum = sum + objectives_list_normalised[i][l]
#     obj_balance_normalised.append(sum)

# # best_obj_1_index = np.argsort(obj_1_normalised)
# # best_obj_2_index = np.argsort(obj_2_normalised)

# best_obj_index = [[] for _ in range(len(objectiveNames))]
# for z in range(len(objectiveNames)):
#     best_obj_index[z] = np.argsort(obj_normalised[z])
# best_obj_balance_index = np.argsort(obj_balance_normalised)

# for x in range(len(best_obj_index)):
#     if best_obj_index[0][-1] == best_obj_index[x][-1]:
#          best_obj_index[x] = best_obj_index[x][:-1] 
# #ifEveryObjIndexLastItemEqual = False

# #for x in range(len(best_obj_index)):
#  #   if best_obj_index[0][-1] == best_obj_index[x][-1]:
#   #      ifEveryObjIndexLastItemEqual = True
#    # else:
#     #    break

# #if ifEveryObjIndexLastItemEqual == True:
#  #   for  x in range(1,len(best_obj_index)):
#   #      best_obj_index[x] = best_obj_index[x][:-1] 

# for x in range(len(best_obj_index)):
#     if best_obj_index[x][-1] == best_obj_balance_index[-1]:
#         best_obj_balance_index = best_obj_balance_index[:-1]
#     if len(best_obj_balance_index)== 1:
#         break
# #        break


    



# # for x in range(len(best_obj_index) - 1):
# #     # 如果相邻元素的最后一个元素相等
# #     if best_obj_index[x+1][-1] == best_obj_index[x][-1]:
# #         # 从相邻元素中移除最后一个元素，直到最后一个元素
# #         for _ in range(len(best_obj_index[x+1]) - len(best_obj_index[x])):
# #             best_obj_index[x+1].pop()

# # if (best_obj_2_index[-1] == best_obj_1_index[-1]):
# #     best_obj_2_index = best_obj_2_index[:-1] # remove last element

# # for i in range(len(best_obj_index)): 
# #    if best_obj_index[i][-1] == best_obj_balance_index[-1]:
# #        best_obj_balance_index = best_obj_balance_index[:-1]
# #        break

# # while (best_obj_balance_index[-1] == best_obj_1_index[-1] or best_obj_balance_index[-1] == best_obj_2_index[-1]):
# #     best_obj_balance_index = best_obj_balance_index[:-1]

# # Placeholders / Dummy variables for conditioning to avoid same solution being proposed twice
# # best_obj_1_index, best_obj_2_index, best_obj_balance_index = [], [], []
# # best_obj_2_index = 101
# # best_obj_balance_index = 102



# solutionNameIndex = []

# for i in range(len(best_obj_index)): 
#     solutionNameIndex.append(best_obj_index[i][-1])
#     best_solutions.append(savedSolutions[best_obj_index[i][-1]*num_parameters:best_obj_index[i][-1]*num_parameters+num_parameters])

# best_solutions.append(savedSolutions[best_obj_balance_index[-1]*num_parameters:best_obj_balance_index[-1]*num_parameters+num_parameters])


reply2 = {}
# solutionNameIndex = [float(x) for x in solutionNameIndex]


# reply['objectives'] = objectivesInput
reply2['saved_solutions'] = savedSolutions
reply2['saved_objectives'] = savedObjectives
# reply2['objectives_normalised'] = train_obj.tolist()
# reply2['best_solutions'] = best_solutions
# reply['solutionNameList'] = solutionNameList
reply2['BestSolutionIndex'] = BestSolutionIndex


reply['success'] = success
reply['message'] = message
reply.update(reply2)

sys.stdout.write("Content-Type: application/json")

sys.stdout.write("\n")
sys.stdout.write("\n")

sys.stdout.write(json.dumps(reply,indent=1))
sys.stdout.write("\n")

# print("Hello from Python!")

# Close the log file
sys.stdout.close()
sys.stderr.close()
