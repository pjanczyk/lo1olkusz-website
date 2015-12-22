angular.module('timetableViewer', ['timetableEditor'])
    .controller('ViewerController', function () {
        var viewer = this;

        viewer.timetable = timetable;
        if (viewer.timetable === null) {
            viewer.timetable = new Timetable();
        }

        viewer.save = function () {
            var form = document.getElementById('form');
            form.value.value = angular.toJson(viewer.timetable);
            form.submit();
        }
    });