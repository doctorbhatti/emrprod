<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthy Life Clinic | Prescription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 72mm;
            /* Adjust to printer width */
            margin: auto;
            padding: 5mm;
            background: #ffffff;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            max-width: 60mm;
            /* Adjust logo size for thermal printer */
            margin-bottom: 5px;
        }

        .header h1 {
            font-size: 16px;
            color: #000;
            margin: 0;
        }

        .header p {
            font-size: 12px;
            color: #555;
        }

        .section {
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .label {
            font-weight: bold;
            color: #000;
            width: 30mm;
            /* Adjust width for alignment */
        }

        .value {
            flex-grow: 1;
            text-align: right;
        }

        /* Prescription item style */
        .prescription-item {
            display: flex;
            justify-content: space-between;
            background: #f9f9f9;
            margin: 2mm 0;
            padding: 5mm;
            font-size: 14px;
            /* Increase font size for readability */
            border-radius: 2mm;
        }

        .prescription-item strong {
            font-weight: bold;
            color: #000;
        }

        .medication,
        .dosage {
            width: 45%;
            /* Adjust the width to ensure side-by-side alignment */
        }

        ol.prescription-list {
            padding-left: 20px;
        }

        ol.prescription-list li {
            counter-increment: list-item;
        }

        ol.prescription-list li::before {
            content: counter(list-item) ". ";
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
            color: #777;
        }

        .page-break {
            page-break-after: always;
        }

        /* Remove unnecessary margins and padding for printing */
        @media print {

            body,
            html {
                margin: 0;
                padding: 0;
                width: 72mm;
                /* Ensure print area fits */
            }

            .container {
                border: none;
                padding: 0;
                box-shadow: none;
            }

            .info-row {
                font-size: 10px;
            }

            .header img {
                max-width: 50mm;
            }

            .prescription-item {
                padding: 3mm;
                font-size: 12px;
                /* Reduce size slightly for better print output */
            }

            .footer {
                font-size: 8px;
            }

            /* Hide elements not needed for print */
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        @if($prescription->prescriptionDrugs()->count() > 0 || $prescription->prescriptionPharmacyDrugs()->count() > 0)
            <div class="header">
                <img style="width:60px; height: 60px;" src="{{ asset($currentLogo) }}" alt="Clinic Logo"> <!-- Logo path -->
                <h1>{{$patient->clinic->name}}</h1>
                <p>Your health, our priority</p>
                <h6>{{$patient->clinic->address}}</h6>
            </div>

            <div class="section">
                <div class="info-row">
                    <div class="label">Patient Name:</div>
                    <div class="value">{{$patient->first_name}} {{$patient->last_name}}</div>
                </div>
                <div class="info-row">
                    <div class="label">Age:</div>
                    <div class="value">{{$patient->dob ? App\Lib\Utils::getAge($patient->dob) : ""}}</div>
                </div>
                <div class="info-row">
                    <div class="label">Date:</div>
                    <div class="value">{{App\Lib\Utils::getFormattedDate($prescription->created_at)}}</div>
                </div>
                <div class="info-row">
                    <div class="label">Gender:</div>
                    <div class="value">{{$patient->gender}}</div>
                </div>
            </div>

            <div class="section prescription-info">
                <div class="info-row">
                    <div class="label">Remarks:</div>
                    <div class="value">Rs.{{$prescription->remarks ? $prescription->remarks : ""}}</div>
                </div>
            </div>

            <div class="section">
                <h2 style="font-size: 14px;">Prescription</h2>
                <ol class="prescription-list">
                    @foreach($prescription->prescriptionDrugs as $drug)
                        <li class="prescription-item">
                            <div class="medication">
                                <strong> {{$drug->drug->name}}</strong>
                            </div>
                            <div class="dosage">
                                <strong>
                                    ({{$drug->dosage ? $drug->dosage->description : ""}}{{$drug->frequency ? ", " . $drug->frequency->description : ""}}{{$drug->period ? ", " . $drug->period->description : ""}})</strong>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            @if($prescription->prescriptionPharmacyDrugs()->count() > 0)
                <div class="page-break"></div>
                <div class="header">
                    <img style="width:60px; height: 60px;" src="{{ asset($currentLogo) }}" alt="Clinic Logo"> <!-- Logo path -->
                    <h1>{{$patient->clinic->name}}</h1>
                    <p>Your health, our priority</p>
                    <h6>{{$patient->clinic->address}}</h6>
                </div>

                <div class="section">
                    <div class="info-row">
                        <div class="label">Patient Name:</div>
                        <div class="value">{{$patient->first_name}} {{$patient->last_name}}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Age:</div>
                        <div class="value">{{$patient->dob ? App\Lib\Utils::getAge($patient->dob) : ""}}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Date:</div>
                        <div class="value">{{App\Lib\Utils::getFormattedDate($prescription->created_at)}}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Gender:</div>
                        <div class="value">{{$patient->gender}}</div>
                    </div>
                </div>

                <div class="section prescription-info">
                    <div class="info-row">
                        <div class="label">Doctor:</div>
                        <div class="value">{{ auth()->user()->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Contact:</div>
                        <div class="value">{{$patient->clinic->phone}}</div>
                    </div>
                </div>
                <div class="section">
                    <h5 style="font-size: 12px;">Drugs To Be Taken From Pharmacy</h5>
                    <ol class="prescription-list">
                        @foreach($prescription->prescriptionPharmacyDrugs as $pharmacyDrug)
                            <li class="prescription-item">
                                <div class="medication">
                                    <strong> {{$pharmacyDrug->drug}}</strong>
                                </div>
                                <div class="dosage">
                                    <strong>Dosage:</strong> {{$pharmacyDrug->remarks}}
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

            <div class="footer">
                <p>&copy; 2024 Healthy Life Clinic | ERM Systems <br> Developed by Dr. M. Hassan Ashfaq. All rights
                    reserved.</p>
            </div>
        @else
            <div class="alert alert-info" ng-if="prescriptions.length==0" ng-cloak>
                <h4><i class="icon fa fa-info"></i> Sorry!</h4>
                There's no drugs in this prescription to be printed.
            </div>
        @endif
    </div>

    <div class="no-print">
        <button onclick="window.print()">Print</button>
    </div>
</body>

</html>