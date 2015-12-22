angular.module('timetable-editor', [])
    .directive('timetableEditor', function() {
        return {
            restrict: 'E',
            transclude: false,
            scope: { value: '=value' },
            controller: function($scope) {

                function replaceObject(a, b) {
                    var prop;

                    for (prop in a) delete a[prop];
                    for (prop in b) a[prop] = b[prop];
                }

                function encode(day) {
                    var result = "";

                    angular.forEach(day, function(hour, hourNo) {
                        if (hourNo[0] === '$') return;

                        result += hourNo + '. ';
                        hour.forEach(function(subject, i) {
                            if (i > 0) result += ' | ';
                            result += subject.name;
                            if (subject.classroom && subject.group) {
                                result += '; ' + subject.classroom + '; ' + subject.group;
                            }
                            else if (subject.classroom) {
                                result += '; ' + subject.classroom;
                            }
                            else if (subject.group) {
                                result += '; ; ' + subject.group;
                            }

                        });
                        result += '\n';
                    });

                    return result;
                }

                function decode(input) {
                    var day = {};

                    input.split("\n").forEach(function (line, lineNo) {
                        if (/^\s*$/.test(line)) return; // empty line

                        var parts = /^\s*(\d+)\.\s*(.*)\s*$/.exec(line);
                        if (parts === null) {
                            throw {
                                message: "Niepoprawny numer godziny",
                                line: lineNo
                            };
                        }

                        var hourNo = parseInt(parts[1]);
                        var subjectsRaw = parts[2].split('|');

                        var subjects = [];
                        subjectsRaw.forEach(function (subjectRaw) {
                            var subject = {};

                            var parts = subjectRaw.split(";");

                            if (parts.length > 3) {
                                throw {
                                    message: "Niepoprawny format",
                                    line: lineNo
                                };
                            }

                            if (parts.length >= 1)
                                subject.name = parts[0].trim();
                            if (parts.length >= 2)
                                subject.class = parts[1].trim();
                            if (parts.length >= 3)
                                subject.group = parts[2].trim();

                            subjects.push(subject);
                        });

                        day[hourNo] = subjects;
                    });

                    return day;
                }

                $scope.editedDay = undefined;
                $scope.editedText = "abba";

                $scope.dayOfWeek = function(i) {
                    return ["Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek"][i];
                };

                $scope.edit = function(day) {
                    day.$editing = true;
                    day.$raw = encode(day);
                };

                $scope.apply = function(day) {
                    var newDay = decode(day.$raw);
                    replaceObject(day, newDay);
                };

                $scope.discard = function(day) {
                    delete day.$editing;
                    delete day.$raw;
                };
            },
            templateUrl: '/assets/js/timetable-editor.html'
        }
    });