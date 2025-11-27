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
        //$scope.pharmacyDrugs = [];

        $scope.prescriptions = [];
        $scope.showScrollToTop = false;
        $scope.showScrollToBottom = true;
        $scope.duePrescriptions = []; // New array to store "Due" prescriptions

        // Initialize the collapsed state
        $scope.isCollapsed = true; // Start as collapsed (true means hidden)

        // Function to toggle collapse/expand
        $scope.toggleCollapse = function () {
            $scope.isCollapsed = !$scope.isCollapsed; // Toggle the collapsed state
        };

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
                    // Filter prescriptions with "Due" in payment remarks
                    $scope.duePrescriptions = $scope.prescriptions.filter(
                        function (prescription) {
                            return (
                                prescription.payment &&
                                prescription.payment.remarks &&
                                prescription.payment.remarks.includes("Due")
                            );
                        }
                    );
                    console.log(
                        "Filtered Due Prescriptions:",
                        $scope.duePrescriptions
                    ); // Debugging

                    // Ensure Angular updates the view
                    $scope.$applyAsync();
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

        // Function to copy pharmacy drugs to localStorage
        $scope.copyPharmacyDrugsToLocalStorage = function (pharmacyDrugs) {
            if (!pharmacyDrugs || pharmacyDrugs.length === 0) return;
            const drugsToStore = pharmacyDrugs.map((d) => ({
                name: d.drug,
                remarks: d.remarks,
            }));
            localStorage.setItem(
                "previousPharmacyDrugs",
                JSON.stringify(drugsToStore)
            );
            //alert("Pharmacy drugs copied for repeating.");
        };

        // Scroll to top function
        $scope.scrollToTop = function () {
            $window.scrollTo({ top: 0, behavior: "smooth" });
        };

        // Scroll to bottom function
        $scope.scrollToBottom = function () {
            $window.scrollTo({
                top: document.body.scrollHeight,
                behavior: "smooth",
            });
        };

        // Track scrolling to show/hide scroll buttons
        angular.element($window).on("scroll", function () {
            $scope.$apply(function () {
                var pageOffset = $window.pageYOffset;
                var windowHeight = $window.innerHeight;
                var documentHeight = document.body.offsetHeight;

                $scope.showScrollToTop = pageOffset > 100;
                $scope.showScrollToBottom =
                    windowHeight + pageOffset < documentHeight - 100;
            });
        });
    },
]);
