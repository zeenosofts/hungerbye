<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<style>
    body{
        font-family:Georgia, Verdana, Arial, sans-serif !important;
    }
    .label{border:1px solid #000;padding: 10px !important;color: white!important;}
    .label-success{background-color:#5cb85c;}.label-success[href]:focus,.label-success[href]:hover{background-color:#449d44}.label-info{background-color:#5bc0de}.label-info[href]:focus,.label-info[href]:hover{background-color:#31b0d5}.label-warning{background-color:#f0ad4e}.label-warning[href]:focus,.label-warning[href]:hover{background-color:#ec971f}.label-danger{background-color:#d9534f}.label-danger[href]:focus,.label-danger[href]:hover{background-color:#c9302c}.badge{display:inline-block;min-width:10px;padding:3px 7px;font-size:12px;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:middle;background-color:#777;border-radius:10px}.badge:empty{display:none}.btn .badge{position:relative;top:-1px}.btn-group-xs>.btn .badge,.btn-xs .badge{top:0;padding:1px 5px}a.badge:focus,a.badge:hover{color:#fff;text-decoration:none;cursor:pointer}.list-group-item.active>.badge,.nav-pills>.active>a>.badge{color:#337ab7;background-color:#fff}.list-group-item>.badge{float:right}.list-group-item>.badge+.badge{margin-right:5px}.nav-pills>li>a>.badge{margin-left:3px}
    table {
        width: 100%;
        border-collapse: collapse;

    }

    /* Zebra striping */
    tr:nth-of-type(odd) {
        background: #eee;
    }

    th {
        background: #3498db;
        color: white;
        font-weight: bold;
    }

    td, th {
        padding: 5px;
        border: 1px solid #ccc;
        text-align: left;
        font-size: 18px;
    }

    /*
    Max width before this PARTICULAR table gets nasty
    This query will take effect for any screen smaller than 760px
    and also iPads specifically.
    */

</style>
<body>
<h1 align="center">Attendance Report</h1>

    <h5>Employee Name: <b style="color: #3498db">{{$totalHours->username}}</b></h5>
    <h5 style="margin-top: -20px">Hours Worked: <b style="color: #3498db">{{$totalHours->total_worked_hours}}</b></h5>

<table style="border: 1px solid black; text-align: center !important">
    <thead>
    <tr style="font-size: 10px !important; font-weight: bold !important;">
        <th>Name</th>
        <th>Date</th>
        <th>Time In</th>
        <th>Time Out</th>
        <th>TWH</th>
        <th>Status</th>
        <th>Attendance</th>
    </tr>
    </thead>
    <tbody>
    @foreach($user as $row)
    <tr style="font-size: 10px !important;">
        <td>{{$row->name}}</td>
        <td>{{date('M d, Y', strtotime($row->today_date))}}</td>

        @if($row->attendance_status === NULL)
        <td >{{date('H:i:s a', strtotime($row->time_in))}}</td>
        @elseif($row->attendance_status === 1)
        <td><span class="label label-warning">On Leave</span></td>
        @elseif($row->attendance_status === 0)
        <td><span class="label label-danger">On Absence</span></td>
        @endif

        @if($row->attendance_status === NULL)
            <td >{{date('H:i:s a', strtotime($row->time_out))}}</td>
        @elseif($row->attendance_status === 1)
            <td><span class="label label-warning">On Leave</span></td>
        @elseif($row->attendance_status === 0)
            <td><span class="label label-danger">On Absence</span></td>
        @endif

        @if($row->attendance_status === NULL)
            <td >{{$row->total_worked_hours }}</td>
        @elseif($row->attendance_status === 1)
            <td><span class="label label-warning">On Leave</span></td>
        @elseif($row->attendance_status === 0)
            <td><span class="label label-danger">On Absence</span></td>
        @endif

        @if($row->attendance_status === NULL)
            <td>
                @if($row->time_in_extracted == 'Early')
                        <b class="text-success">Early Arrival</b>
                @elseif($row->time_in_extracted == 'Late')
                        <b v-if="row.time_in_extracted == 'Late'" class="text-danger">Late Arrival</b>
                @endif
                    /
                @if($row->time_out_extracted == 'Early')
                    <b class="text-success">Early Out</b>
                @elseif($row->time_out_extracted == 'Late')
                    <b v-if="row.time_in_extracted == 'Late'" class="text-danger">Late Out</b>
                @endif
            </td>
             @elseif($row->attendance_status === 1)
            <td><span class="label label-warning">On Leave</span></td>
        @elseif($row->attendance_status === 0)
            <td><span class="label label-danger">On Absence</span></td>
        @endif
        @if($row->attendance_status === NULL)
            <td><span class="label label-success">Present</span></td>
        @elseif($row->attendance_status === 1)
            <td><span class="label label-warning">On Leave</span></td>
        @elseif($row->attendance_status === 0)
            <td><span class="label label-danger">On Absence</span></td>
        @endif
    </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>


