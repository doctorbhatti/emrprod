<html xmlns="http://www.w3.org/1999/html">

<head>
    <title>Healthy Life Clinic | Prescription</title>
    <!-- Bootstrap 5 CSS  -->
    <link rel="stylesheet" href="{{asset('dist/css/bootstrap@5.3.0.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    h3,
    h4,
    h5 {
        color: #2c3e50;
    }

    h3 {
        font-weight: 600;
    }

    .container-fluid {
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .header-container {
        text-align: center;
        border-bottom: 2px solid #3498db;
        padding: 15px;
    }

    .header-container img {
        width: 80px;
        height: 80px;
    }

    .remarksDiv h4 {
        color: #e74c3c;
        font-weight: bold;
    }

    .col-xs-1 span {
        font-size: 45px;
        color: #3498db;
    }

    ol {
        padding-left: 20px;
    }

    ol li {
        font-size: 22px;
        margin-bottom: 10px;
    }

    .page-break {
        page-break-after: always;
    }

    .footer-container {
        margin-top: 50px;
        text-align: center;
        font-size: 12px;
        color: #95a5a6;
        border-top: 2px dotted #bdc3c7;
        padding-top: 10px;
    }

    .no-print {
        margin-top: 30px;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #2980b9;
        font-size: 16px;
        padding: 10px 20px;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #1e6f9a;
    }

    .alert-info {
        background-color: #eaf2f8;
        color: #3498db;
        border-color: #3498db;
    }

    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }

        .page-break {
            page-break-after: always;
        }

        @page {
            margin: 0;
        }

        body {
            background-color: white;
        }
    }
</style>

<body>
    <div class="container-fluid">
        @if($prescription->prescriptionDrugs()->count() > 0 || $prescription->prescriptionPharmacyDrugs()->count() > 0)
            <div class="col-md-6 col-md-offset-3 col-xs-12">
                <div class="header-container">
                    <h3>
                        <img src="{{ asset($currentLogo) }}" alt="Clinic Logo">
                        <br>
                        {{$patient->clinic->name}}<br>
                        <small>
                            {{$patient->clinic->address}}<br>
                            {{$patient->clinic->phone}}<br>
                            {{$patient->clinic->email}}
                        </small>
                    </h3>
                </div>

                <h5 style="border-bottom: 2px solid #3498db; padding: 15px;">
                    <strong>Patient:</strong> {{$patient->first_name}} {{$patient->last_name}}<br>
                    <strong>Age:</strong> {{$patient->dob ? App\Lib\Utils::getAge($patient->dob) : ""}}<br>
                    <strong>Date:</strong> {{App\Lib\Utils::getFormattedDate($prescription->created_at)}}
                    <div class="remarksDiv">
                        <h4><strong>Rs.{{$prescription->remarks ? $prescription->remarks : ""}}</strong></h4>
                    </div>
                </h5>

                <div class="row" style="border-bottom: 2px solid #3498db; padding: 15px;">
                    <div class="col-xs-1">
                        <span>&#8478;</span>
                    </div>
                    <div class="col-xs-11">
                        <ol>
                            @foreach($prescription->prescriptionDrugs as $drug)
                                <li>
                                    <strong>{{$drug->drug->name}}</strong>
                                    ({{$drug->dosage ? $drug->dosage->description : ""}}
                                    {{$drug->frequency ? ", " . $drug->frequency->description : ""}}
                                    {{$drug->period ? ", " . $drug->period->description : ""}})
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                @if($prescription->prescriptionPharmacyDrugs()->count() > 0)
                    <div class="page-break"></div>
                    <!-- Second slip for pharmacy drugs with repeated clinic info -->
                    <div class="header-container">
                        <h3>
                            <img src="{{ asset($currentLogo) }}" alt="Clinic Logo">
                            <br>
                            {{$patient->clinic->name}}<br>
                            <small>
                                {{$patient->clinic->address}}<br>
                                <!-- {{$patient->clinic->email}} -->
                            </small>
                        </h3>
                    </div>

                    <h5 style="border-bottom: 2px solid #3498db; padding: 15px;">
                        <strong>Patient:</strong> {{$patient->first_name}} {{$patient->last_name}}<br>
                        <strong>Age:</strong> {{$patient->dob ? App\Lib\Utils::getAge($patient->dob) : ""}}<br>
                        <strong>Date:</strong> {{App\Lib\Utils::getFormattedDate($prescription->created_at)}}
                    </h5>
                    <h5 style="border-bottom: 2px solid #3498db; padding: 15px;">
                        <strong>Doctor:</strong> {{ auth()->user()->name }}<br>
                        <strong>Contact:</strong> {{$patient->clinic->phone}}<br>
                    </h5>

                    <h5>Drugs to be taken from a pharmacy:</h5>
                    <ol>
                        @foreach($prescription->prescriptionPharmacyDrugs as $index => $pharmacyDrug)
                            <li>
                                <strong>{{$pharmacyDrug->drug}} <br  ></strong>
                                {{$pharmacyDrug->remarks ? "(Dose: " . $pharmacyDrug->remarks . ")" : ""}}
                            </li>
                        @endforeach
                    </ol>
                @endif

                <div class="footer-container">
                    <p>Healthy Life Clinic | ERM Systems <br> Developed by Dr. M. Hassan Ashfaq <br> All rights reserved</p>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <h4><i class="icon fa fa-info"></i> Sorry!</h4>
                There's no drugs in this prescription to be printed.
            </div>
        @endif
    </div>

    <div class="row no-print">
        <div class="col-md-2 col-md-offset-3">
            <button class="btn btn-primary" onclick="window.close()">
                <i class="fa fa-close"></i> Close
            </button>
        </div>
        <div class="col-md-2 col-md-offset-2">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fa fa-print"></i> Print
            </button>
        </div>
    </div>
</body>

</html>