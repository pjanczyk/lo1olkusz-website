angular.module('timetableBells', [])
    .controller('BellsController', function ($http) {
        var controller = this;

        controller.alerts = [];
        controller.bells = [];
        controller.lastModified = null;

        function load() {
            $http({
                method: 'GET',
                url: '/api/bells',
                headers: {'Accept': 'application/json'}
            }).then(function success(response) {
                controller.bells = response.data.value;
                controller.lastModified = response.data.lastModified;
            }, function error(response) {
                controller.alerts.push({
                    value: response.data.error,
                    type: 'danger'
                });
            });
        }

        load();

        controller.save = function () {
            $http({
                method: 'PUT',
                url: '/api/bells',
                data: angular.toJson(controller.bells),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then(function success(response) {
                controller.alerts.push({
                    value: 'Zapisano',
                    type: 'success'
                });
                load();
            }, function error(response) {
                controller.alerts.push({
                    value: response.data.error,
                    type: 'danger'
                });
            });
        };

        controller.addRow = function () {
            controller.bells.push(["", ""]);
        };

        controller.removeRow = function () {
            controller.bells.pop();
        };
    });