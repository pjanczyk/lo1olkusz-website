/**
 * Copyright (C) 2016  Piotr Janczyk
 * Part of lo1olkusz unofficial app - website.
 * Available under GNU Affero General Public License <http://www.gnu.org/licenses/>.
 */

angular.module('timetableBells', [])
    .controller('BellsController', function ($http) {
        var controller = this;

        controller.alerts = [];
        controller.bells = [];
        controller.lastModified = null;

        function load() {
            $http.get('/api/bells')
                .then(function success(response) {
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
            $http.put('/api/bells', angular.toJson(controller.bells))
                .then(function success() {
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