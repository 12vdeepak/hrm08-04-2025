
<table>
    <thead>
        <tr>{{$data['title']}}</tr>
        <tr>
        <th>ID</th>
                <th class="border-bottom-0">Name</th>
                <th class="border-bottom-0">Email</th>
                
                @for ($i = 1; $i <= $data['days']; $i++)
                    <th class="border-bottom-0 w-5">{{ $i }}</th>
                @endfor
         


                
        </tr>

       
    </thead>
    
    <tbody>
    
    
    @foreach ($data['attendance_data'] as $data)
            <tr>
            <td>
                        {{ $data['detail']['id'] }}
</td>
                    <td>
                        {{ $data['detail']['name'] . ' ' . $data['detail']['lastname']}}
                    </td>
                    <td>
                        {{ $data['detail']['email'] }}
                    </td>

                    @foreach ($data['record']['attendance'] as $record)
                        <td >
                           @if ( date('Y-m-d',strtotime($record['date'])) > date('Y-m-d'))
                               -
                            @elseif ($record['status'] == 0)
                                A
                            @elseif($record['status'] == 0.5)
                                 HD

                            @elseif($record['status'] == 1)
                               P
                            @elseif($record['status'] == 2)
                                O
                            @elseif($record['status'] == 3)
                                W
                            @elseif($record['status'] == 4)
                              H
                            @endif
                        </td>
                    @endforeach
</tr>
            @endforeach
            
   
    </tbody> 
</table>
