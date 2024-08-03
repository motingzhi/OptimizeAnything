#!/usr/bin/python3 

#/Users/fengyu.li/anaconda3/bin/python3
#/usr/bin/env python3

import sys
#sys.path.append("/usr/local/lib/python3.10/site-packages")
import json
import cgi
import numpy as np
import sqlite3

from import_all import *



# Initialize the basic reply message
reply = {}
message = "Necessary objects imported."
success = True
test = False
# Read provided formData
formData = cgi.FieldStorage()

# Define function for checking that required parameters have been submitted
def checkFormData(data, expectedArgs):
    argsDefined = True
    for i in range(0,len(expectedArgs)):
        if expectedArgs[i] not in data:
            argsDefined = False
            break
    return argsDefined

# Define function for converting param points to csv string
def arrayToCsv(values):
    csv_string = ""
    n = values.shape[0]
    for i in range(0,n):
        if (i > 0):
            csv_string += ","
        csv_string += str(values[i])
    return csv_string

expectedArgs = ['parameter-names', 'parameter-bounds', 'objective-names', 'objective-bounds', 'objective-min-max']
formValuesDefined = checkFormData(formData, expectedArgs)

if not formValuesDefined:
    success = False
    message = "Form values not defined."
else:
    conn = sqlite3.connect("../Data/database.db")
    c = conn.cursor()

    createFunctionTableQuery_1 = '''CREATE TABLE IF NOT EXISTS definitions_parameters (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        param_name TEXT, 
        param_lower_bound INTEGER,
        param_upper_bound INTEGER  
        )'''

    c.execute(createFunctionTableQuery_1)
    conn.commit()

    createFunctionTableQuery_2 = '''CREATE TABLE IF NOT EXISTS definitions_objectives (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        obj_name TEXT, 
        obj_lower_bound INTEGER,
        obj_upper_bound INTEGER,
        obj_min_max TEXT 
        )'''

    c.execute(createFunctionTableQuery_2)
    conn.commit()

    parameterNames = (formData['parameter-names'].value).split(',')
    parameterBounds = (formData['parameter-bounds'].value).split(',')
    objectiveNames = (formData['objective-names'].value).split(',')
    objectiveBounds = (formData['objective-bounds'].value).split(',')
    objectiveMinMax = (formData['objective-min-max'].value).split(',')
    test = True
    reply['parameterNames'] = parameterNames
    reply['parameterBounds'] = parameterBounds
    reply['objectiveNames'] = objectiveNames
    reply['objectiveBounds'] = objectiveBounds


    # typeStr = "start"
    # timeStr = str(time.time())

    for i in range(len(parameterNames)):
        query = '''INSERT INTO definitions_parameters (param_name,param_lower_bound,param_upper_bound) VALUES (?, ?, ?)'''
        c.execute(query, (parameterNames[i], parameterBounds[2*i], parameterBounds[2*i+1]))

        if (i<2):
            query = '''INSERT INTO definitions_objectives (obj_name,obj_lower_bound,obj_upper_bound,obj_min_max) VALUES (?, ?, ?, ?)'''
            c.execute(query, (objectiveNames[i], objectiveBounds[2*i], objectiveBounds[2*i+1], objectiveMinMax[i]))

        conn.commit()
    
    conn.close()

    message = json.dumps("success")


reply['success'] = success
reply['message'] = message
# reply['test'] = test


sys.stdout.write("Content-Type: application/json")

sys.stdout.write("\n")
sys.stdout.write("\n")

sys.stdout.write(json.dumps(reply,indent=1))
sys.stdout.write("\n")

# # 生成 HTML 页面发送数据到 define-2.php
# sys.stdout.write("""
# <!DOCTYPE html>
# <html>
# <head>
#     <script>
#         function sendToDefinePHP(parameterNames, parameterBounds) {
#             var xhr = new XMLHttpRequest();
#             xhr.open("POST", "/define-2.php", true);
#             xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
#             xhr.onreadystatechange = function () {
#                 if (xhr.readyState === 4 && xhr.status === 200) {
#                     console.log("Data sent to define-2.php successfully");
#                 }
#             };
#             xhr.send("parameter-names=" + encodeURIComponent(parameterNames) + "&parameter-bounds=" + encodeURIComponent(parameterBounds));
#         }

#         // 获取参数数据并发送
#         var parameterNames = '{}';
#         var parameterBounds = '{}';
#         sendToDefinePHP(parameterNames, parameterBounds);
#     </script>
# </head>
# <body>
#     <p>Data processed successfully. Redirecting...</p>
# </body>
# </html>
# """.format(','.join(parameterNames), ','.join(parameterBounds)))


sys.stdout.close()
