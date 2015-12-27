angular.module('timetableImporter', ['timetableEditor'])
    .controller('ImporterCtrl', function ($http) {

        /**
         * Converts text of which each line is in format '<element0> -> <element1> -> <element2>'
         * to array of arrays
         * @param {String} input
         * @returns {[][]}
         */
        function parseList(input) {
            var results = [];

            var lines = input.split('\n');
            lines.forEach(function (line) {
                var re, match;

                re = /^\s*"(.*)"\s*->\s*"(.*)"\s*->\s*"(.*)"\s*$/;
                match = re.exec(line);
                if (match !== null) {
                    results.push(match.slice(1));
                }

                re = /^\s*"(.*)"\s*->\s*"(.*)"\s*$/;
                match = re.exec(line);
                if (match !== null) {
                    results.push(match.slice(1));
                }
            });

            return results;
        }

        var importer = this;

        importer.inputCsv = "";
        importer.inputReplace = "";
        importer.inputSubjects = "";
        importer.timetables = [];

        $http.get('/assets/js/csv-replace-default.txt').then(function (response) {
            importer.inputReplace = response.data;
        });
        $http.get('/assets/js/csv-subjects-default.txt').then(function (response) {
            importer.inputSubjects = response.data;
        });

        importer.parseCsv = function () {
            var replace = parseList(importer.inputReplace);
            var subjects = parseList(importer.inputSubjects);
            importer.timetables = parseCsv(importer.inputCsv, replace, subjects);
        };

        importer.discard = function (className) {
            delete importer.timetables[className];
        };

        importer.save = function (className) {
            var timetable = importer.timetables[className];

            $http.post('/dashboard/timetables/' + className, angular.toJson(timetable))
                .then(function success(response) {
                    alert(response.data.message);
                    importer.discard(className);
                }, function error(response) {
                    alert('Błąd:\n' + response.data.error);
                });
        }
    });