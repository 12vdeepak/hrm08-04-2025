
<table>
    <thead>
        <tr>{{$data['title']}}</tr>
        <tr>
            <th><b>Name</b></th>
            <th><b>Email</b></th>
            <th><b>Date</b></th>
            <th><b>First In</b></th>
            <th><b>Location(First In)</b></th>
            <th><b>Last Out</b></th>
            <th><b>Location(Last Out)</b></th>
            <th><b>Time</b></th>
            <th><b>Comment</b></th>
            <th><b>Status</b></th>
            <th><b>Screen Time</b></th>
        </tr>
    </thead>
    
     <tbody>
        @foreach ($data['checkins_data'] as $check_in)
            <tr>
                <td rowspan="{{ $data['days']+1 }}">
                    {{ $check_in['detail']['name'] }}
                </td>
                 <td rowspan="{{ $data['days']+1 }}">
                    {{ $check_in['detail']['email'] }}
                </td>
            </tr>
                @foreach ($check_in['record'] as $record)
                   <tr> 
                        <td>
                            {{ $record['date'] }}
                        </td>
                        <td>
                            {{ $record['check_in'] }}
                        </td>
                        <td>
                            {{ $record['check_in_location'] }}
                        </td>
                        <td>
                            {{ $record['check_out'] }}
                        </td>
                        <td>
                            {{ $record['check_out_location'] }}
                        </td>
                        <td>
                            {{ $record['time'] }}
                        </td>
                        <td >
                            {{ $record['comment'] }}
                        </td>
                        <td >
                            {{ $record['status'] }}
                        </td>
                         <td >
                            {{ $record['screen-time'] }}
                        </td>
                   </tr>
                @endforeach
            
        @endforeach
    </tbody> 
</table>
