<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Consent Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
        h1 {
            font-size: 2.5rem;
            color: #000;
        }
        h3, h4 {
            font-size: 1.5rem;
            color: #000;
        }
        p {
            font-size: 1rem;
            color: #000;
        }
        .consent-text {
            font-size: 1rem;
            color: #000;
        }
        .highlight {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="text-center">😊Welcome to the user study on the service "Optimize anything"!</h1><br><br>
        <h3 class="text-center">First please read the task description and the information sheet for participants.</h3><br>

        <h4 class="text-center">TASK DESCRIPTION</h4>
        <h4 class="text-center">1. In this task you need to perform an optimization task through using the service "optimize anything"</h4>
        <h4 class="text-center">2. You need to answer the questionnaire after the task is completed.</h4>
        <br>
        <br>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="consent-text">

                    <h4 class="text-center">INFORMATION SHEET FOR PARTICIPANTS</h4>
                    <h5 class="text-center">You need to read this and agree with the consent form at the bottom of the page.</h5>
                    <br>
                    <br>
                    <p><strong>“Optimize Anything User Study”</strong></p>
                    <p><span class="highlight">Purpose:</span> To understand how people formulate and solve optimization problems.</p>
                    <p><span class="highlight">Responsible PI:</span> Prof. Antti Oulasvirta (tel +358 503841561). The members of the group have experience in over 50+ controlled studies in human-computer interaction. There are no reported incidents of ethical misconduct.</p>
                    <p><span class="highlight">Time commitment:</span> 30 – 45 minutes.</p>
                    <p><span class="highlight">Description of the study:</span></p>
                    <ul>
                        <li>You need to perform an optimization task through using the service "optimize anything".</li>
                        <li>Answer the questionnaire after the task is completed.</li>
                    </ul>
                    <p><span class="highlight">Eligibility for the study:</span> Anyone with a computer.</p>
                    <p><span class="highlight">Compensation:</span> Follow the compensation policy on Prolific.</p>
                    <p><span class="highlight">Voluntary participation:</span> Participation in the study is voluntary. You have the right to discontinue participation at any time without obligation to disclose a specific reason.</p>
                    <p><span class="highlight">The rights of the study participant:</span> Participation in the study requires forgoing the following rights:</p>
                    <ul>
                        <li>The right to access stored personal information collected during the study.</li>
                        <li>The right to correct said personal information.</li>
                        <li>The right to oppose the processing of said personal information.</li>
                        <li>The right to delete said information.</li>
                    </ul>
                    <p>If however it is possible to achieve the aims of the study and the achievement of the purpose is not greatly hindered, we will actualize your rights as defined in the GDPR. The extent of your rights is related to the legal basis of processing of your personal data and exercising your rights required proof of identity.</p>
                    <p><span class="highlight">Possible risks and their prevention:</span> All experiments do not physical effort other than using a computer.</p>
                    <p><span class="highlight">Communication with the research staff during testing:</span> You can send an email to tingzhimo@gmail.com or directly contact the researcher on Prolific for any questions or issues you encounter during the study.</p>
                    <p><span class="highlight">Collection of data:</span></p>
                    <ul>
                        <li>Answers to questionnaires.</li>
                        <li>Logging data acquired from interacting with the web service.</li>
                        <li>Demographic information: sex, age, occupation.</li>
                    </ul>
                    <p>Personal information is collected to analyze the correlation and communication within the subject of the research.</p>
                    <p><span class="highlight">Who will process your personal information:</span> Antti Oulasvirta, Fengyu Li.</p>
                    <p><span class="highlight">Transferring data outside EU:</span> No.</p>
                    <p><span class="highlight">Anonymity, secure storage, confidentiality:</span> The data will be used for scientific purposes only and are confidential. All data will be anonymized. No explicit clues of your identity will be left to the stored data. All data will be stored securely and accessible only to the members.</p>
                    <p><span class="highlight">Measures taken in cases of unexpected incidental findings:</span> This study has not been designed to provide clinical information.</p>
                    <p><span class="highlight">Insurance coverage:</span> You are covered by Aalto-level insurances for accidents and damages during the study.</p>
                    <p><span class="highlight">Further information:</span> In question regarding research, you can contact the responsible researcher. You can also contact the Aalto University data protection officer if you have questions about data processing and protection: Jari Söderström (dpo@aalto.fi, +358505665186). If you notice a violation in the data protection legislation, you can contact the Data Protection Ombudsman (http://www.tietosuoja.fi/en).</p>
                    <p><span class="highlight">If you agree to take part in the study, please read and agree on the consent form below by selecting the "agree" options displayed on the web page.</span></p>
                    <h4 class="text-center">Computational Design of User Interfaces</h4>
                    <h4 class="text-center">CONSENT FROM</h4>
                    <p>I agree to participate in the user interface experiment by the CBL group. I have read and understood the study information sheet given to me.</p>
                    <p>I have understood that the material and research data is gathered for scientific purposes only. The purpose and nature of the study has been explained to me in writing. I have sufficient information on the process of the study.</p>
                    <p>I understand that my participation in the study is completely voluntary and that I have the right to discontinue my participation at any stage without any consequences.</p>
                    <p>I give permission for my data to be recorded in the described manner.</p>
                    <p>I understand that I can ask to take a break at any time during the study.</p>
                    <p>It has been explained to me that a designated researcher will at my request provide me with additional details of the general principles of the study and its progress or of the results concerning myself.</p>
                    <p>I understand that anonymity will be ensured by disguising my identity. I have been explained who are the different parties involved in the research that have access to my data. I understand the practices of storing, protecting, and using the data.</p>
                    <p>I know that the collected data will not be presented to a third party without my written consent. I know that the research group may ask for a professional consultation on possible unexpected incidental findings without separate consent provided that the anonymity of the results has been ensured. Any type of commercial exploitation of the results is prohibited.</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 text-left mt-3">
                <div class="form-check">
                    <p><strong>1. You understand that a fully anonymized subset of the data may be released to other research groups for the purposes mentioned above, if you give permission to it. </strong></p>
                    <p>Please select one of below options</p>

                    <input class="form-check-input" type="radio" name="dataRelease" id="agreeRelease" value="agreeRelease">
                    <label class="form-check-label" for="agreeRelease">
                        I agree to releasing anonymized extracts from my data.
                    </label><br>
                    <input class="form-check-input" type="radio" name="dataRelease" id="conditionalRelease" value="conditionalRelease">
                    <label class="form-check-label" for="conditionalRelease">
                        I agree to releasing anonymized extracts from my data only if I am informed about the research groups in question. I have been told what that subset will be.
                    </label><br>
                    <input class="form-check-input" type="radio" name="dataRelease" id="disagreeRelease" value="disagreeRelease">
                    <label class="form-check-label" for="disagreeRelease">
                        I do not agree to releasing extracts from my data.
                    </label>
                </div>

                <div class="form-check mt-3">
                    <p><strong>2. You understand that extracts from possible interviews/questionnaires may be quoted in subsequent publications, and you agree to anonymized quotation/publication of extracts from the interview/ questionnaires.</strong></p>
                    <input class="form-check-input" type="radio" name="quotationPermission" id="agreeQuotation" value="agreeQuotation">
                    <label class="form-check-label" for="agreeQuotation">
                         I understand and agree.
                    </label><br>
                    <input class="form-check-input" type="radio" name="quotationPermission" id="disagreeQuotation" value="disagreeQuotation">
                    <label class="form-check-label" for="disagreeQuotation">
                        I do not agree.
                    </label>
                </div>

                <div class="form-check mt-3">
                    <p><strong>3. By selecting this option, you agree to all other terms in the PDF consent form displayed on this web, confirm your participation in this study and agree to volunteer as a study subject.</strong></p>
                    <input class="form-check-input" type="checkbox" id="confirmParticipation">
                    <label class="form-check-label" for="confirmParticipation">
                        I confirm my participation in this study and agree to volunteer as a study subject.
                    </label>
                </div>

                <br>
                <p><strong>If you don't agree with this consent form, you can leave the study.</strong></p>

            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 text-center mt-3">
                <button id="startButton" class="btn btn-primary" disabled>Start</button>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            localStorage.clear();
        };

        const startButton = document.getElementById('startButton');
        const confirmParticipation = document.getElementById('confirmParticipation');

        const dataReleaseOptions = document.getElementsByName('dataRelease');
        const quotationOptions = document.getElementsByName('quotationPermission');

        function checkConsent() {
            const selectedDataRelease = Array.from(dataReleaseOptions).find(option => option.checked);
            const selectedQuotation = Array.from(quotationOptions).find(option => option.checked);

            if (selectedDataRelease && selectedQuotation && confirmParticipation.checked) {
                if (
                    selectedDataRelease.id !== 'disagreeRelease' &&
                    selectedQuotation.id !== 'disagreeQuotation'
                ) {
                    startButton.disabled = false;
                } else {
                    startButton.disabled = true;
                }
            } else {
                startButton.disabled = true;
            }
        }

        dataReleaseOptions.forEach(option => option.addEventListener('change', checkConsent));
        quotationOptions.forEach(option => option.addEventListener('change', checkConsent));
        confirmParticipation.addEventListener('change', checkConsent);

        document.getElementById('startButton').addEventListener('click', function() {
            var ismanual = 1; //true
            window.location.href = 'index_id.php';  // 跳转到index_id.php页面
            localStorage.setItem("ismanual", ismanual);
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>