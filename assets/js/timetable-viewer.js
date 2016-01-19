/**
 * Copyright (C) 2016  Piotr Janczyk
 * Part of lo1olkusz unofficial app - website.
 * Available under GNU Affero General Public License <http://www.gnu.org/licenses/>.
 */

angular.module('timetableViewer', ['timetableEditor'])
    .controller('ViewerController', function ($http) {
        var controller = this;

        controller.className = null;
        controller.addingNew = true;
        controller.lastModified = null;
        controller.value = null;
        controller.alerts = [];

        controller.delete = function () {
            $http.delete('/api/timetables/' + controller.className)
                .then(function success() {
                    controller.alerts.push({
                        value: 'Usunięto',
                        type: 'success'
                    });
                    controller.value = [{}, {}, {}, {}, {}];
                    controller.className = null;
                    controller.lastModified = null;
                    controller.addingNew = true;
                }, function error(response) {
                    controller.alerts.push({
                        value: response.data.error,
                        type: 'danger'
                    });
                });
        };

        controller.save = function () {
            $http.put('/api/timetables/' + controller.className, angular.toJson(controller.value))
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

        function load() {
            $http.get('/api/timetables/' + controller.className)
                .then(function success(response) {
                    controller.value = response.data.value;
                    controller.lastModified = response.data.lastModified;
                    controller.addingNew = false;
                }, function error(response) {
                    controller.alerts.push({
                        value: response.data.error,
                        type: 'danger'
                    });
                });
        }

        if (className) {
            controller.className = className;
            load();
        }
        else {
            controller.value = [{}, {}, {}, {}, {}];
        }
    });