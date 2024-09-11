angular.module("HIS").controller("RecordController", [
    "$scope",
    "$http",
    "api",
    "$filter",
    "$timeout",
    "$window",
    "$rootScope",
    function ($scope, $http, api, $filter, $timeout, $window, $rootScope) {
        $scope.baseUrl = "";
        $scope.token = "";
        $scope.id = null;

        $scope.prescriptions = [];

        //to listen to the new records that are being added.
        $rootScope.$on("PrescriptionIssuedEvent", function (event, data) {
            $scope.loadMedicalRecords();
        });

        /**
         * Initial function to load records
         */
        $scope.loadMedicalRecords = function () {
            api.getMedicalRecords($scope.baseUrl, $scope.token, $scope.id).then(
                function (data) {
                    if (data.status == 1) {
                        $scope.prescriptions = data.prescriptions;
                    }
                }
            );
        };

        // Function to copy drugs to localStorage
        $scope.copyDrugsToLocalStorage = function (prescribedDrugs) {
            var drugsData = prescribedDrugs.map(function (drug) {
                return {
                    id: drug.drug ? drug.drug.id || "" : "",
                    name: drug.drug ? drug.drug.name || "" : "", // Nullable: Get drug name or default to ""
                    type:
                        drug.drug && drug.drug.quantity_type
                            ? drug.drug.quantity_type.drug_type || ""
                            : "", // Nullable: Get drug type or default to ""
                    dosage: drug.dosage ? drug.dosage.description || "" : "", // Nullable: Get dosage or default to ""
                    doseId: drug.dosage ? drug.dosage.id || "" : "", // Include dose id
                    frequency: drug.frequency
                        ? drug.frequency.description || ""
                        : "", // Nullable: Get frequency or default to ""
                    period: drug.period ? drug.period.description || "" : "", // Nullable: Get period or default to ""
                    quantity: drug.quantity || 0, // Nullable: Get quantity or default to 0
                    type: drug.type || 0,
                };
            });

            localStorage.setItem("prescribedDrugs", JSON.stringify(drugsData));
            //alert("Drugs copied to localStorage");
        };
    },
]);
