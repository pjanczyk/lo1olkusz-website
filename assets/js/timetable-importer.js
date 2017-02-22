/**
 * Copyright (C) 2016  Piotr Janczyk
 * Part of lo1olkusz unofficial app - website.
 * Available under GNU Affero General Public License <http://www.gnu.org/licenses/>.
 */

angular.module('timetableImporter', ['timetableEditor'])
    .controller('ImporterCtrl', function ($scope, $http) {
        var importer = this;

        importer.timetables = [];

        importer.loadFile = function () {
            var files = document.getElementById('files').files;
            if (!files.length) {
                return;
            }

            var file = files[0];
            var reader = new FileReader();

            reader.onload = function (e) {
                importer.timetables = JSON.parse(e.target.result);
                $scope.$apply();
            };

            reader.readAsText(file);
        };

        importer.discard = function (className) {
            delete importer.timetables[className];
        };

        importer.save = function (className) {
            var timetable = importer.timetables[className];

            $http.put('/api/timetables/' + className, angular.toJson(timetable))
                .then(function success(response) {
                    alert(response.data.message);
                    importer.discard(className);
                }, function error(response) {
                    alert('Błąd:\n' + response.data.error);
                });
        }
    });