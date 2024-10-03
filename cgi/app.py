from fastapi import FastAPI, Form
from typing import List, Optional
# import json
# import logging
import time 
from fastapi import FastAPI, Request


# import sys
# import os
# import cgi

# import numpy as np
# from matplotlib import pyplot as plt
# import pandas as pd
import torch
# import torchvision
# import os
#from botorch.test_functions.multi_objective import BraninCurrin
from botorch.models.gp_regression import SingleTaskGP
from botorch.models.transforms.outcome import Standardize
from gpytorch.mlls.exact_marginal_log_likelihood import ExactMarginalLogLikelihood
from botorch.utils.transforms import unnormalize
from botorch.utils.sampling import draw_sobol_samples #1
from botorch.optim.optimize import optimize_acqf
# from botorch.optim.optimize import optimize_acqf, optimize_acqf_list

#from botorch.acquisition.objective import GenericMCObjective
from botorch.utils.multi_objective.scalarization import get_chebyshev_scalarization
from botorch.utils.multi_objective.box_decompositions.non_dominated import NondominatedPartitioning
from botorch.acquisition.multi_objective.monte_carlo import qExpectedHypervolumeImprovement
#from botorch.utils.sampling import sample_simplex
from botorch import fit_gpytorch_model
# from botorch.acquisition.monte_carlo import qExpectedImprovement, qNoisyExpectedImprovement
#from botorch.sampling.samplers import SobolQMCNormalSampler
from botorch.sampling.normal import SobolQMCNormalSampler
#from botorch.exceptions import BadInitialCandidatesWarning
from botorch.utils.multi_objective.pareto import is_non_dominated
from botorch.utils.multi_objective.hypervolume import Hypervolume
from concurrent.futures import ThreadPoolExecutor


# 配置日志
# 配置日志输出到本地文件
# logging.basicConfig(
#     filename='/var/log/fastapi/my_fastapi.log',  # 确保路径正确
#     level=logging.INFO,
#     format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
#     datefmt='%Y-%m-%d %H:%M:%S',
# )


# logger = logging.getLogger(__name__)

# torch.set_num_threads(8)  # 假设使用4个线程，可以根据你的硬件情况调整

app = FastAPI()

# # 使用 startup 事件监听器记录 "FastAPI 已运行"
# @app.on_event("startup")
# async def log_startup_event():
#     logger.info("FastAPI 已运行")

# # 示例 API 路由
# @app.get("/")
# async def read_root():
#     return {"message": "FastAPI is running"}


# API 1: 对应于 newSolution_optimize.py 的逻辑
@app.post("/new_solution_optimize/")
async def new_solution(
    parameterNames: str = Form(...),  # 必填字段，对应 formData['parameter-names']
    parameterBounds: str = Form(...),  # 必填字段，对应 formData['parameter-bounds']
    objectiveNames: str = Form(...),  # 必填字段，对应 formData['objective-names']
    objectiveBounds: str = Form(...),  # 必填字段，对应 formData['objective-bounds']
    objectiveMinMax: str = Form(...),  # 必填字段，对应 formData['objective-min-max']
    goodSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['good-solutions']
    badSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['bad-solutions']
    currentSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['current-solutions']
    savedSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['saved-solutions']
    savedObjectives: Optional[str] = Form(None),  # 可选字段，对应 formData['saved-objectives']
    objectivesInput: Optional[str] = Form(None),  # 可选字段，对应 formData['objectives-input']
    solutionNameList: Optional[str] = Form(None)  # 可选字段，对应 formData['solution-name-list']
    
):
    # 处理字段：将 None 替换为默认值，或处理非 None 值
    parameterNames = parameterNames.split(',') if parameterNames else []
    objectiveNames = objectiveNames.split(',') if objectiveNames else []
    objectiveMinMax = objectiveMinMax.split(',') if objectiveMinMax else []
    parameterBounds = parameterBounds.split(',') if parameterBounds else []
    objectiveBounds = objectiveBounds.split(',') if objectiveBounds else []

    goodSolutions = goodSolutions.split(',') if goodSolutions else []
    badSolutions = badSolutions.split(',') if badSolutions else []
    currentSolutions = currentSolutions.split(',') if currentSolutions else []
    savedSolutions = savedSolutions.split(',') if savedSolutions else []
    savedObjectives = savedObjectives.split(',') if savedObjectives else []
    objectivesInput = objectivesInput.split(',') if objectivesInput else []
    solutionNameList = solutionNameList.split(',') if solutionNameList else []
    
    
    # 其余代码和之前一样
    BATCH_SIZE = 1 # Number of design parameter points to query at next iteration
    NUM_RESTARTS = 10 # Used for the acquisition function number of restarts in optimization
    RAW_SAMPLES = 1024 # Initial restart location candidates
    N_ITERATIONS = 35 # Number of optimization iterations
    MC_SAMPLES = 512 # Number of samples to approximate acquisition function
    N_INITIAL = 5
    SEED = 2 

    n_sample =  1 #Define the n_sample in function generate_initial_data
    num_parameters = len(parameterNames)
    parameter_bounds = torch.zeros(2, num_parameters)
    parameter_bounds_normalised = torch.zeros(2, num_parameters)#

    ##处理currentSolutions [[],[]]
    currentSolutions = list(map(float, currentSolutions))
    currentSolutions = [currentSolutions[i:i + num_parameters] for i in range(0, len(currentSolutions), num_parameters)]


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

    bad_solutions.append(currentSolutions[-1*num_parameters:])


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
                if (objectives_min_max[i] == "minimize"): # MINIMISE (SMALLER VALUES CLOSER TO 1)
                    obj_tensor_norm[j][i] = -2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) + 1
                elif (objectives_min_max[i] == "maximize"): # MAXIMISE (LARGER VALUES CLOSER TO -1)
                    obj_tensor_norm[j][i] =  2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) - 1
        return obj_tensor_norm

    def check_duplicates(tensor):

        if tensor.ndim == 1:
            tensor_list = [tensor.tolist()]  # 将 1D tensor 变为 2D 列表
        else:
            tensor_list = tensor.tolist()  # 直接转换为 2D 列表
        for row in tensor_list:
                if row in currentSolutions:
                    return True  # 如果找到匹配的，返回 True
            
        return False  # 没有找到匹配的，返回 False
    
    def generate_initial_data(n_samples=n_sample):
        # generate training data
        train_x = draw_sobol_samples(bounds=parameter_bounds_normalised, n=n_samples, q=1).squeeze(1)

        train_x = train_x.type(torch.DoubleTensor)
        train_x_actual = torch.round(unnormalise_parameters(train_x))
        # Check for duplicates and regenerate if necessary
        attempt = 0
        max_attempts = 10  # Prevent infinite loops
        # print("Initial solution: ", train_x_actual)
        while check_duplicates(train_x_actual) and attempt < max_attempts:
                train_x = draw_sobol_samples(bounds=parameter_bounds_normalised, n=n_samples, q=1).squeeze(1)
                train_x = train_x.type(torch.DoubleTensor)
                train_x_actual = torch.round(unnormalise_parameters(train_x))
                attempt += 1
        return train_x, train_x_actual
    

    train_x, train_x_actual = generate_initial_data()
    currentSolutions.append(train_x_actual.tolist())#和sample的数目保持一致 用户不能跳过
    # allSolutions.append(train_x_actual.tolist())#和sample的数目保持一致 用户不能跳过

    reply = {
        'solution': currentSolutions,
        'objectives': objectivesInput,
        'bad_solutions': bad_solutions,
        'saved_solutions': savedSolutions,
        'saved_objectives': savedObjectives,
        'train_x_actual': train_x_actual.tolist(),
        'parameterNames': parameterNames,
        'success': True,
        'message': "Necessary objects imported."
    }

    return reply

# API 2: 对应于 newSolution_initial.py 的逻辑
@app.post("/new_solution_initial/")
async def next_evaluation(
    parameterNames: str = Form(...),  # 必填字段，对应 formData['parameter-names']
    parameterBounds: str = Form(...),  # 必填字段，对应 formData['parameter-bounds']
    objectiveNames: str = Form(...),  # 必填字段，对应 formData['objective-names']
    objectiveBounds: str = Form(...),  # 必填字段，对应 formData['objective-bounds']
    objectiveMinMax: str = Form(...),  # 必填字段，对应 formData['objective-min-max']
    goodSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['good-solutions']
    badSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['bad-solutions']
    currentSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['current-solutions']
    savedSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['saved-solutions']
    savedObjectives: Optional[str] = Form(None),  # 可选字段，对应 formData['saved-objectives']
    objectivesInput: Optional[str] = Form(None),  # 可选字段，对应 formData['objectives-input']
    solutionNameList: Optional[str] = Form(None),  # 可选字段，对应 formData['solution-name-list']
    solutionName: Optional[str] = Form(None),  # 可选字段，对应 
    objective_Measurements: Optional[str] = Form(None)  # 可选字段，对应 

):
    # 处理字段：将 None 替换为默认值，或处理非 None 值
    parameterNames = parameterNames.split(',') if parameterNames else []
    objectiveNames = objectiveNames.split(',') if objectiveNames else []
    objectiveMinMax = objectiveMinMax.split(',') if objectiveMinMax else []
    parameterBounds = parameterBounds.split(',') if parameterBounds else []
    objectiveBounds = objectiveBounds.split(',') if objectiveBounds else []

    goodSolutions = goodSolutions.split(',') if goodSolutions else []
    badSolutions = badSolutions.split(',') if badSolutions else []
    currentSolutions = currentSolutions.split(',') if currentSolutions else []
    savedSolutions = savedSolutions.split(',') if savedSolutions else []
    savedObjectives = savedObjectives.split(',') if savedObjectives else []
    objectivesInput = objectivesInput.split(',') if objectivesInput else []
    solutionNameList = solutionNameList.split(',') if solutionNameList else []
    solutionName = solutionName.split(',') if solutionName else []
    objective_Measurements = objective_Measurements.split(',') if objective_Measurements else []

    # 提取和处理数据
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


    for i in range(len(savedSolutions)):
        savedSolutions[i] = float(savedSolutions[i])

    if (len(objectivesInput) != 0):
        objectivesInputPlaceholder = []
        for i in range(int(len(objectivesInput)/len(objectiveNames))):
                sub_list = [float(x) for x in objectivesInput[len(objectiveNames)*i:len(objectiveNames)*i+len(objectiveNames)]]
                objectivesInputPlaceholder.append(sub_list)
            # objectivesInputPlaceholder.append([float(objectivesInput[2*i]), float(objectivesInput[2*i+1]),float(objectivesInput[2*i+2])])
        objectivesInput = objectivesInputPlaceholder
    objectivesInput.append(obj)

    savedSolutions.append(nested_list[int(len(savedObjectives)/len(objectiveNames))])
    savedObjectives.append(obj)
    solutionNameList.append(solutionName)

    bad_solutions.append(currentSolutions[-1*num_parameters:])

    
    # 返回响应
    reply = {
        'solution': currentSolutions,
        'objectives': objectivesInput,
        'bad_solutions': bad_solutions,
        'saved_solutions': savedSolutions,
        'saved_objectives': savedObjectives,
        'solutionNameList': solutionNameList,
        'success': True,
        'message': "Next evaluation complete."
    }

    return reply




# API 3: 对应于 next_evaluation.py 的逻辑
@app.post("/next_evaluation/")
async def next_evaluation(
    parameterNames: str = Form(...),  # 必填字段，对应 formData['parameter-names']
    parameterBounds: str = Form(...),  # 必填字段，对应 formData['parameter-bounds']
    objectiveNames: str = Form(...),  # 必填字段，对应 formData['objective-names']
    objectiveBounds: str = Form(...),  # 必填字段，对应 formData['objective-bounds']
    objectiveMinMax: str = Form(...),  # 必填字段，对应 formData['objective-min-max']
    goodSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['good-solutions']
    badSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['bad-solutions']
    currentSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['current-solutions']
    savedSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['saved-solutions']
    savedObjectives: Optional[str] = Form(None),  # 可选字段，对应 formData['saved-objectives']
    objectivesInput: Optional[str] = Form(None),  # 可选字段，对应 formData['objectives-input']
    solutionNameList: Optional[str] = Form(None),  # 可选字段，对应 formData['solution-name-list']
    solutionName: Optional[str] = Form(None),  # 可选字段，对应 
    objective_Measurements: Optional[str] = Form(None)  # 可选字段，对应 

):
    # start_time = time.time()

    # # 记录接收到的请求数据
    # logger.info("Received next evaluation request")

    # 处理字段：将 None 替换为默认值，或处理非 None 值
    parameterNames = parameterNames.split(',') if parameterNames else []
    objectiveNames = objectiveNames.split(',') if objectiveNames else []
    objectiveMinMax = objectiveMinMax.split(',') if objectiveMinMax else []
    parameterBounds = parameterBounds.split(',') if parameterBounds else []
    objectiveBounds = objectiveBounds.split(',') if objectiveBounds else []

    goodSolutions = goodSolutions.split(',') if goodSolutions else []
    badSolutions = badSolutions.split(',') if badSolutions else []
    currentSolutions = currentSolutions.split(',') if currentSolutions else []
    savedSolutions = savedSolutions.split(',') if savedSolutions else []
    savedObjectives = savedObjectives.split(',') if savedObjectives else []
    objectivesInput = objectivesInput.split(',') if objectivesInput else []
    solutionNameList = solutionNameList.split(',') if solutionNameList else []
    solutionName = solutionName.split(',') if solutionName else []
    objective_Measurements = objective_Measurements.split(',') if objective_Measurements else []


    BATCH_SIZE = 1 # Number of design parameter points to query at next iteration
    NUM_RESTARTS = 10 # Used for the acquisition function number of restarts in optimization
    RAW_SAMPLES = 1024 # Initial restart location candidates
    N_ITERATIONS = 35 # Number of optimization iterations
    MC_SAMPLES = 512 # Number of samples to approximate acquisition function
    N_INITIAL = 5
    SEED = 2 

    # 提取和处理数据
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
                if (objectives_min_max[i] == "minimize"): # MINIMISE (SMALLER VALUES CLOSER TO 1)
                    obj_tensor_norm[j][i] = -2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) + 1
                elif (objectives_min_max[i] == "maximize"): # MAXIMISE (LARGER VALUES CLOSER TO -1)
                    obj_tensor_norm[j][i] =  2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) - 1
        return obj_tensor_norm

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
    
    def optimize_qehvi_parallel(model, train_obj, sampler, parameter_bounds=parameter_bounds_normalised):
        """并行化优化 qEHVI 的计算，并选择最佳候选解"""

        def run_optimize_acqf():
            # 划分非主导空间为不相交的矩形
            partitioning = NondominatedPartitioning(ref_point=obj_ref_point, Y=train_obj)
            acq_func = qExpectedHypervolumeImprovement(
                model=model,
                ref_point=obj_ref_point.tolist(),  # 使用已知的参考点
                partitioning=partitioning,
                sampler=sampler,
            )
            # 优化
            candidates, acq_value = optimize_acqf(
                acq_function=acq_func,
                bounds=parameter_bounds,  # 归一化的参数范围
                q=BATCH_SIZE,
                num_restarts=NUM_RESTARTS,
                raw_samples=RAW_SAMPLES,  # 初始化启发式使用的样本数
                options={"batch_limit": 5, "maxiter": 200, "nonnegative": True},
                sequential=True,
            )
            return candidates, acq_value

        # 使用线程池并行化多个重启过程
        with ThreadPoolExecutor(max_workers=NUM_RESTARTS) as executor:
            futures = [executor.submit(run_optimize_acqf) for _ in range(NUM_RESTARTS)]
            results = [f.result() for f in futures]

        # 选择采集值最大的候选解
        best_candidate = None
        best_acq_value = float('-inf')  # 初始化为负无穷
        for candidates, acq_value in results:
            if acq_value > best_acq_value:
                best_acq_value = acq_value
                best_candidate = candidates

        # 最终选择采集值最高的候选解
        new_x = best_candidate
        new_x_actual = unnormalise_parameters(new_x, parameter_bounds)

        return new_x, new_x_actual
    

    obj = [float(x) for x in objective_Measurements]
    # logging.debug(obj)


    for i in range(len(currentSolutions)):
        currentSolutions[i] = float(currentSolutions[i])

    for i in range(len(savedSolutions)):
        savedSolutions[i] = float(savedSolutions[i])

    #处理objective
    # train_obj_actual = torch.tensor([[obj1, obj2]], dtype=torch.float64)
    if (len(savedObjectives) != 0):
        objectivesInputPlaceholder = []
        for i in range(int(len(savedObjectives)/len(objectiveNames))):
                sub_list = [float(x) for x in savedObjectives[len(objectiveNames)*i:len(objectiveNames)*i+len(objectiveNames)]]
                objectivesInputPlaceholder.append(sub_list)
            # objectivesInputPlaceholder.append([float(objectivesInput[2*i]), float(objectivesInput[2*i+1]),float(objectivesInput[2*i+2])])
        objectivesInput = objectivesInputPlaceholder

    # if len(savedSolutions)/len(parameterNames) >= 2*(len(parameterNames)+1):
    #     objectivesInput = []

    objectivesInput.append(obj)
    savedObjectives.append(obj)
    # print(len(objectivesInput))
    solutionNameList.append(solutionName)

    train_obj_actual = torch.tensor(objectivesInput, dtype=torch.float64)
    train_obj = normalise_objectives(train_obj_actual)

    parametersPlaceholder = []

    # if len(savedSolutions)/len(parameterNames) < 2*(len(parameterNames)+1):
    for i in range(int(len(savedSolutions)/num_parameters)):
        parametersPlaceholder.append(savedSolutions[i*num_parameters:i*num_parameters+num_parameters]) #切片[2:4] 是 2，3，
            # parametersPlaceholder.append(currentSolutions[m*num_parameters:m*num_parameters+num_parameters]) #搞清用作训练的到底是current solutions里的哪些。
    # else:
    parametersPlaceholder.append(currentSolutions[-num_parameters:])

    #搞清用作训练的到底是current solutions里的哪些,是最后一个，还是全部
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

    ##以下用作检查new_x_actual是否有重复的
    new_x_actual = torch.round(new_x_actual)

    # Update training points
    train_x = torch.cat([train_x, new_x])
    train_x_actual = torch.cat([train_x_actual, new_x_actual])

    currentSolutions.append(train_x_actual.tolist()[-1])
       # 记录请求结束的时间

    end_time = time.time()
        # 计算处理时长
    # processing_time = end_time - start_time
    # logger.info(f"API /next_evaluation processed in {processing_time:.4f} seconds")


    # 返回响应
    reply = {
        'solution': currentSolutions,
        'objectives': objectivesInput,
        'bad_solutions': bad_solutions,
        'saved_solutions': savedSolutions,
        'saved_objectives': savedObjectives,
        'solutionNameList': solutionNameList,
        'success': True,
        'message': "Next evaluation complete."
    }
    # logger.info(f"Response data: {reply}")

    return reply


# API 4: 对应于 refine_solution.py 的逻辑
@app.post("/refine_solution/")
async def refine_solution(
    parameterNames: str = Form(...),  # 必填字段，对应 formData['parameter-names']
    parameterBounds: str = Form(...),  # 必填字段，对应 formData['parameter-bounds']
    objectiveNames: str = Form(...),  # 必填字段，对应 formData['objective-names']
    objectiveBounds: str = Form(...),  # 必填字段，对应 formData['objective-bounds']
    objectiveMinMax: str = Form(...),  # 必填字段，对应 formData['objective-min-max']
    goodSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['good-solutions']
    badSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['bad-solutions']
    currentSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['current-solutions']
    savedSolutions: Optional[str] = Form(None),  # 可选字段，对应 formData['saved-solutions']
    savedObjectives: Optional[str] = Form(None),  # 可选字段，对应 formData['saved-objectives']
    objectivesInput: Optional[str] = Form(None),  # 可选字段，对应 formData['objectives-input']对应savedobjectives
    solutionNameList: Optional[str] = Form(None),  # 可选字段，对应 formData['solution-name-list']
    solutionName: Optional[str] = Form(None),  # 可选字段，对应 
    objective_Measurements: str = Form(...)  # 可选字段，对应 

):
    # 处理字段：将 None 替换为默认值，或处理非 None 值
    parameterNames = parameterNames.split(',') if parameterNames else []
    objectiveNames = objectiveNames.split(',') if objectiveNames else []
    objectiveMinMax = objectiveMinMax.split(',') if objectiveMinMax else []
    parameterBounds = parameterBounds.split(',') if parameterBounds else []
    objectiveBounds = objectiveBounds.split(',') if objectiveBounds else []

    goodSolutions = goodSolutions.split(',') if goodSolutions else []
    badSolutions = badSolutions.split(',') if badSolutions else []
    currentSolutions = currentSolutions.split(',') if currentSolutions else []
    savedSolutions = savedSolutions.split(',') if savedSolutions else []
    savedObjectives = savedObjectives.split(',') if savedObjectives else []
    objectivesInput = objectivesInput.split(',') if objectivesInput else []
    solutionNameList = solutionNameList.split(',') if solutionNameList else []
    solutionName = solutionName.split(',') if solutionName else []
    if objective_Measurements:
        objective_Measurements = objective_Measurements.split(',')

    BATCH_SIZE = 1 # Number of design parameter points to query at next iteration
    NUM_RESTARTS = 10 # Used for the acquisition function number of restarts in optimization
    RAW_SAMPLES = 1024 # Initial restart location candidates
    N_ITERATIONS = 35 # Number of optimization iterations
    MC_SAMPLES = 512 # Number of samples to approximate acquisition function
    N_INITIAL = 5
    SEED = 2 

    # 提取和处理数据
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

    def initialize_model(train_x, train_obj):
        # define models for objective and constraint
        model = SingleTaskGP(train_x, train_obj, outcome_transform=Standardize(m=train_obj.shape[-1]))
        mll = ExactMarginalLogLikelihood(model.likelihood, model)
        return mll, model

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
                if (objectives_min_max[i] == "minimize"): # MINIMISE (SMALLER VALUES CLOSER TO 1)
                    obj_tensor_norm[j][i] = -2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) + 1
                elif (objectives_min_max[i] == "maximize"): # MAXIMISE (LARGER VALUES CLOSER TO -1)
                    obj_tensor_norm[j][i] =  2*((obj_tensor_actual[j][i] - objective_bounds[0][i])/(objective_bounds[1][i] - objective_bounds[0][i])) - 1
        return obj_tensor_norm

    def unnormalise_parameters(x_tensor, x_bounds = parameter_bounds):
        x_actual = torch.zeros(1, num_parameters)
        for i in range(num_parameters):
            x_actual[0][i] = x_tensor[0][i]*(x_bounds[1][i] - x_bounds[0][i]) + x_bounds[0][i]
        return x_actual
    

    def optimize_qehvi(model, train_obj, sampler, parameter_bounds=parameter_bounds):
        """Optimizes the qEHVI acquisition function, and returns a new candidate and observation."""
        # partition non-dominated space into disjoint rectangles
        partitioning = NondominatedPartitioning(ref_point=obj_ref_point, Y=train_obj)
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
        return new_x, new_x_actual
    
    obj = [float(x) for x in objective_Measurements]

    for i in range(len(currentSolutions)):
        currentSolutions[i] = float(currentSolutions[i])

    for i in range(len(savedSolutions)):
        savedSolutions[i] = float(savedSolutions[i])


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

    train_obj_actual = torch.tensor(objectivesInput, dtype=torch.float64)
    train_obj = normalise_objectives(train_obj_actual)

    parametersPlaceholder = []

    for i in range(int(len(savedSolutions)/num_parameters)):
        parametersPlaceholder.append(savedSolutions[i*num_parameters:i*num_parameters+num_parameters]) #切片[2:4] 是 2，3，

    parametersPlaceholder.append(currentSolutions[-num_parameters:])

    train_x_actual = torch.tensor(parametersPlaceholder, dtype=torch.float64)
    savedSolutions.append(train_x_actual.tolist()[-1])
    train_x = normalise_parameters(train_x_actual)
    torch.manual_seed(SEED)

    parameter_bounds_refined = torch.zeros(2, num_parameters)
    parameter_bounds_range_refined = []
    for i in range(num_parameters):
        parameter_bounds_refined[0][i] = currentSolutions[len(currentSolutions)-num_parameters+i] - parameter_bounds_range[i]*0.05
        if (parameter_bounds_refined[0][i] < parameter_bounds[0][i]):
            parameter_bounds_refined[0][i] = parameter_bounds[0][i]
        parameter_bounds_refined[1][i] = currentSolutions[len(currentSolutions)-num_parameters+i] + parameter_bounds_range[i]*0.05
        if (parameter_bounds_refined[1][i] > parameter_bounds[1][i]):
            parameter_bounds_refined[1][i] = parameter_bounds[1][i]
        parameter_bounds_range_refined.append(parameter_bounds_refined[1][i] - parameter_bounds_refined[0][i])

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
    # Optimize acquisition functions and get new observations
    new_x, new_x_actual = optimize_qehvi(model, train_obj, qehvi_sampler, parameter_bounds_refined)
    new_x_actual = torch.round(new_x_actual)

    # Update training points
    train_x = torch.cat([train_x, new_x])
    train_x_actual = torch.cat([train_x_actual, new_x_actual])
    # train_obj = torch.cat([train_obj, new_obj])
    # train_obj_actual = torch.cat([train_obj_actual, new_obj_actual])

    currentSolutions.append(train_x_actual.tolist()[-1])
    
    
    # 返回响应
    reply = {
        'solution': currentSolutions,
        'objectives': objectivesInput,
        'bad_solutions': bad_solutions,
        'saved_solutions': savedSolutions,
        'saved_objectives': savedObjectives,
        'solutionNameList': solutionNameList,
        'success': True,
        'message': "Next evaluation complete."
    }

    return reply