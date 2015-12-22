var app = angular.module('editor', ['timetable-editor']);

app.controller('ImporterCtrl', function ($http) {
    var importer = this;

    importer.inputCsv = "";
    importer.inputReplace = "";
    importer.inputSubjects = "";

    importer.timetables = [];


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

        $http({
            method: 'POST',
            url: '/dashboard/timetables',
            data: $.param({
                'save': true,
                'class': className,
                'value': angular.toJson(timetable)
            }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'text/plain'
            }
        }).then(function success(response) {
            alert(response.data);
            importer.discard(className);
        }, function error(response) {
            alert('Błąd:\n' + response.data);
        });
    }
});

function parseList(input) {
    var results = [];

    var lines = input.split('\n');
    lines.forEach(function (line) {
        var parts = line.split('->');
        if (parts.length >= 2) {
            results.push(parts);
        }
    });

    return results;
}

