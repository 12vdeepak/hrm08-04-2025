<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2;">
    <div style="padding:0px 10px;">
        <p style="font-size:1em;">{{ $details['body'] }}</p>

        <p style="font-size:1rem;">Details<b></b></p>

        <b>Name :</b> {{ $details['name'] }}<br>
        <b>Leave Type : </b> {{ $details['type'] }}<br>
        <b>Subject : </b> {{ $details['subject'] }}<br>
        <b>Description : </b> {{ $details['description'] }}<br>
        <b>Start Date : </b> {{ $details['start_date'] }}<br>
        <b>End Date : </b> {{ $details['end_date'] }}<br>

        <!-- Add approval buttons -->
        <div style="margin-top: 30px; text-align: center;">
            <p style="font-size: 1.1em; margin-bottom: 20px;"><b>Action Required:</b></p>

            <a href="{{ $details['approve_url'] }}"
                style="background-color: #28a745; color: white; padding: 12px 25px;
                      text-decoration: none; border-radius: 5px; margin-right: 15px;
                      display: inline-block; font-weight: bold;">
                ✓ APPROVE
            </a>

            <a href="{{ $details['disapprove_url'] }}"
                style="background-color: #dc3545; color: white; padding: 12px 25px;
                      text-decoration: none; border-radius: 5px;
                      display: inline-block; font-weight: bold;">
                ✗ DISAPPROVE
            </a>
        </div>

        <p style="margin-top: 20px; font-size: 0.9em; color: #666;">
            <b>Note:</b> Click on the appropriate button above to approve or disapprove this leave request.
        </p>
    </div>
</div>
