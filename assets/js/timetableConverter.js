String.prototype.startsWith = function (prefix) {
    return this.slice(0, prefix.length) == prefix;
}

function parse(input) {
	
	var timetable = new Array(5);
	var lines = input.split("\n");
	var day;
	
	lines.forEach(function (line, lineNo) {
		if (line.startsWith("#")) {//day number
			var dayNo = parseInt(line.substring(1).trim());
			if (dayNo == NaN || dayNo > 5 || dayNo < 1) {
				throw {
					message: "Niepoprawny format numeru dnia ",
					line: lineNo
				};
			}
			
			day = [];
			timetable[dayNo] = day;
		}
		else if (/^\s*$/.test(line) === false) {
			if (day == null) {
				throw {
					message: "Niezdefiniowany dzień",
					line: lineNo
				};
			}
			
			var parts = line.split(".", 2);
			if (parts.length != 2) {
				throw {
					message: "Niepoprawny format",
					line: lineNo
				};
			}
			
			var subject = {};

			subject.hour = parseInt(parts[0].trim());
			if (subject.hour == NaN) {
				throw {
					message: "Niepoprawny numer godziny",
					line: lineNo
				};
			}
			
			var subjectParts = parts[1].split("|");
			
			if (subjectParts.length > 3) {
				throw {
					message: "Niepoprawny format",
					line: lineNo
				};
			}
			
			if (subjectParts.length >= 1)
				subject.name = subjectParts[0].trim();
			if (subjectParts.length >= 2)
				subject.class = subjectParts[1].trim();
			if (subjectParts.length >= 3)
				subject.group = subjectParts[2].trim();
				
			day.push(subject);
		}
	});
	
	return timetable;
}

function encode(timetables) {
	var result = "";
	
	Object.keys(timetables).forEach(function(className) {
		result += className + '\n';
		var timetable = timetables[className];
		timetable.forEach(function(day, dayNo) {
			result += '#' + dayNo + '\n';
			Object.keys(day).forEach(function(hourNo) {
				result += hourNo + '. ';
				var hour = day[hourNo];
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
		});
	});
	
	return result;
}