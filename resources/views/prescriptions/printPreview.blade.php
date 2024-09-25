<html xmlns="http://www.w3.org/1999/html">

<head>
    <title>Healthy Life Clinic | Prescription</title>
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}" media="print">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<style>
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }

    .page-break {
        page-break-after: always;
    }

    @page {
        size: auto;
        /* auto is the initial value */
        margin: 0;
        /* this affects the margin in the printer settings */
    }

    .pClass {
        text-align: center;
    }

    .remarksDiv {
        text-align: left;
    }
</style>

<body>
    {{--The ID to be printed--}}
    <div class="container-fluid">
        @if($prescription->prescriptionDrugs()->count() > 0 || $prescription->prescriptionPharmacyDrugs()->count() > 0)
            <div class="col-md-6 col-md-offset-3 col-xs-12">
                <div class="row container-fluid">

                    <h3 class="center-block text-center">
                        <img src="{{ asset($currentLogo) }}" alt="Clinic Logo" style="width:60px; height: 60px;">
                        <br>
                        {{$patient->clinic->name}}<br>
                        <small>

                            {{$patient->clinic->address}}<br>
                            {{$patient->clinic->phone}}<br>
                            {{$patient->clinic->email}}
                        </small>
                    </h3>
                    <h5 style="border-bottom: 2px solid black">
                        <strong>Patient :</strong> {{$patient->first_name}} {{$patient->last_name}}
                        <br>
                        <strong>{{$patient->dob ? App\Lib\Utils::getAge($patient->dob) : ""}}</strong>
                        <br>
                        <strong>{{App\Lib\Utils::getFormattedDate($prescription->created_at)}}</strong>
                        <div class="remarksDiv">
                            <h4><strong>Rs.{{$prescription->remarks ? $prescription->remarks : ""}}</strong></h4>
                        </div>
                    </h5>

                    <div class="row" style="border-bottom: 2px solid black">
                        <div class="col-xs-1">
                            <span style="font-size: 40px">&#8478;</span>
                        </div>
                        <div class="col-xs-11">
                            <ol class="col-xs-12">
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
                        @if($prescription->prescriptionPharmacyDrugs()->count() > 0)
                            <div class="page-break"></div>
                            <h3 class="center-block text-center">
                                <img src="{{asset('images/printlogo.png')}}" style="width:60px; height: 60px;">
                                <br>
                                {{$patient->clinic->name}}<br>
                                <small>
                                    {{$patient->clinic->address}}<br>
                                    {{$patient->clinic->phone}}<br>
                                    {{$patient->clinic->email}}
                                </small>
                            </h3>
                            <h5 style="border-bottom: 2px solid black">
                                <strong>Patient :</strong> {{$patient->first_name}} {{$patient->last_name}}
                                <br>
                                <strong>{{$patient->dob ? App\Lib\Utils::getAge($patient->dob) : ""}}</strong>
                                <br>
                                <strong>{{App\Lib\Utils::getFormattedDate($prescription->created_at)}}</strong>
                                <!-- <span class="pull-right">{{App\Lib\Utils::getFormattedDate($prescription->created_at)}}</span> -->
                            </h5>
                            <h5>Drugs to be taken from a pharmacy</h5>
                            <ol class="col-xs-12">
                                @foreach($prescription->prescriptionPharmacyDrugs as $index => $pharmacyDrug)
                                    <li>
                                        <strong>{{$pharmacyDrug->drug}}</strong>
                                        {{$pharmacyDrug->remarks ? "(Dose : " . $pharmacyDrug->remarks . ")" : ""}}
                                    </li>
                                @endforeach
                            </ol>
                        @endif

                    </div>

                    <h5 class="col-xs-6 col-xs-offset-3"
                        style="margin-top: 100px;border-top: 3px dotted black;padding-top: 5px">
                    </h5>
                </div>
                <div class="divClass">
                    <p class="pClass"><!-- FIX -->
                        <small>Healthy Life Clinic | ERM Systems <br> Developed by Dr. M. Hassan Ashfaq <br> All rights
                            reserved </small>
                    </p><!-- FIX -->
                </div>


            </div>
        @else
            {{-- Info message if there are no prescriptions to be issued --}}
            <div class="col-xs-6 col-xs-offset-3">
                <div class="alert alert-info" ng-if="prescriptions.length==0" ng-cloak>
                    <h4><i class="icon fa fa-info"></i> Sorry!</h4>
                    There's no drugs in this prescription to be printed.
                </div>
            </div>
        @endif
    </div>

    <div class="row margin-top container-fluid no-print">
        <div class="col-xs-6 col-xs-offset-3">
            <div class="alert alert-info" ng-if="prescriptions.length==0" ng-cloak>
                <h4><i class="icon fa fa-info"></i> Important!</h4>
                When printing the prescriptions, avoid printing <strong>headers and footers</strong> by changing
                <strong>Print Settings</strong> from the print preview.
            </div>
        </div>

        <div class="col-md-2 col-md-offset-3">
            <button class="btn btn-primary pull-left" onclick="window.close()">
                <i class="fa fa-close" aria-hidden="true"></i> Close
            </button>
        </div>
        {{--@if($prescription->prescriptionPharmacyDrugs()->count()>0)--}}
        <div class="col-md-2 col-md-offset-2">
            <button class="btn btn-primary pull-right" onclick="window.print()">
                <i class="fa fa-print" aria-hidden="true"></i> Print
            </button>
        </div>
        {{--@endif--}}
    </div>

</body>

</html>