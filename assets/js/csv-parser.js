(function (){

	/**
	 * @param {string} text Text in a single cell of CSV to be parsed.
	 * @param {string[][]} replace Array of [<regular_expression>, <replace_with>]
	 * @param {string[][]} subjectNames Array of [<regex>, <subjectName>, <group>]
	 */
	function decodeSubjectList(text, replace, subjectNames) {
		// trim whitespaces
		text = text.trim();
		
		// perform replacements
		replace.forEach(function(e) {
			text = text.replace(new RegExp(e[0], 'g'), e[1]);
		});
		
		subjectNames.forEach(function(e) {
			// 'A2.47 BIOL 47' -> '@A2%A2%.47 @Biologia%% 47'
			var replaceWith = '@' + e[1] + '%' + (e[2] ? e[2] : '') + '%';
			text = text.replace(new RegExp(e[0], 'g'), replaceWith);
		});

		var results = [];
		
		text.split('@').forEach(function(part) {
			part = part.trim();
			if (part == '') return;

			var subject;

			var re = /^(.*)%(.*)%(.*)$/;
			var match = re.exec(part);
			if (match !== null) {
				subject = {
					"name": match[1].trim(),
					"group": match[2].trim(),
					"classroom": match[3].trim()
				};
			}
			else { //unclassified case
				subject = {
					"name": part,
					"error": true
				};
			}
			
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
					result[className] = [ {}, {}, {}, {}, {} ];
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