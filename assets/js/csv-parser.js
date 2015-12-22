(function (){

	/**
	 * @param {string} text Text in a single cell of CSV to be parsed.
	 * @param {string[][]} replace Array of [<regular_expression>, <replaceWith>]
	 * @param {string[][]} subjectNames Array of [<regex>, <subjectName>, <group>]
	 */
	function decodeSubjectList(text, replace, subjectNames) {
		// trim whitespaces
		text = text.trim();
		
		// perform replacements
		replace.forEach(function(e) {
			text = text.replace(new RegExp(e[0], 'g'), e[1]);
		});

        var replacedList = [];
		
		subjectNames.forEach(function(e) {
			// 'A2 H BIOL 47' -> '@ H @ 47'
            var expr = e[0];
            var nameReplace = e[1];
            var groupReplace = e[2] ? e[2] : '';

			text = text.replace(new RegExp(expr, 'g'), function(match) {
                var re = new RegExp('^' + expr + '$');

                replacedList.push({
                    name: match.replace(re, nameReplace),
                    group: match.replace(re, groupReplace)
                });

                return '@';
			});
		});

		var results = [];
		
		var parts = text.split('@');
        var part0 = parts.shift().trim();
        if (part0 !== '') {
            results.push({
                name: part0,
                error: true
            });
        }

        parts.forEach(function(part, idx) {
			part = part.trim();

            var replaced = replacedList[idx];
			var subject = {
                name: replaced.name,
                group: replaced.group,
                classroom: part
            };
			results.push(subject);
		});
		
		return results;
	}

	/**
	 * Converts class name in format "III b" to format "3b"
	 * @returns {string}
     */
	function decodeClassName(className) {
		var re = /^([I|i]+)\s*([a-zA-Z])$/;
		var match = re.exec(className);
		if (match === null) {
			return className;
		}
		else {
			return match[1].length + match[2].toLowerCase();
		}
	}
	
	this.parseCsv = function(input, replace, subjectNames) {
		
		var lines = input.split("\n");
		
		var daysOfWeek = ["PONIEDZIAŁEK", "WTOREK", "ŚRODA", "CZWARTEK", "PIĄTEK"];
		
		var result = {};
		
		var currentDay = -1;
		var currentClasses;
		
		lines.forEach(function (line) {
			var joined = line.replace(/;/g, '').trim();
			
			if (joined == "") return; //skip empty lines

			var day = daysOfWeek.indexOf(joined);
			if (day != -1) { //header with day of week
				currentDay = day;
			}
			else if (line[0] == ";") { //header with names of classes
				var parts = line.split(";").slice(1);
				var classCount = parts.length / 2;
				
				currentClasses = [];
				
				for (var i = 0; i < classCount; i++) {
					var className = parts[2 * i] + parts[2 * i + 1];
					className = className.trim();
					className = decodeClassName(className);
					
					if (className == "") break;
					
					currentClasses[i] = className;
					result[className] = [ {}, {}, {}, {}, {} ]; //empty timetable
				}
			}
			else { //subjects
				var parts = line.split(";");

				var hourNo = parseInt(parts[0]);

				parts = parts.slice(1);
				
				for (var i = 0; i < currentClasses.length; i++) {
					var className = currentClasses[i];

					var subjects = [];
					
					[2 * i, 2 * i + 1].forEach(function(j) {
						var s = decodeSubjectList(parts[j], replace, subjectNames);
						subjects = subjects.concat(s);
					});
					
					if (subjects.length > 0) {
						result[className][currentDay][hourNo] = subjects;
					}
				}
				
			}
		});
		
		return result;
	}

}());