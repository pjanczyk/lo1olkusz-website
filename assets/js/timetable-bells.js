angular.module('timetableBells', [])
    .controller('BellsController', function () {
        var controller = this;

        controller.bells = bells;
        if (controller.bells === null) {
            controller.bells = [];
        }

        controller.save = function () {
            var form = document.getElementById('form');
            form.value.value = angular.toJson(controller.bells);
            form.submit();
        };

        controller.addRow = function() {
            controller.bells.push(["", ""]);
        };

        controller.removeRow = function() {
            controller.bells.pop();
        };
    });