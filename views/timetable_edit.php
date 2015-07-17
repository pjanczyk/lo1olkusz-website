<h2>Change</h2>
<form action="/timetable" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="class">Class</label>
        <input type="text" class="form-control" name="class" id="class" placeholder="Class name"/>
    </div>
    <div class="form-group">
        <label for="timetable">Timetable</label>
        <textarea class="form-control" name="timetable" id="timetable" rows="10" placeholder="Timetable content"></textarea>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox"> Delete
        </label>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>