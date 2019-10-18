@include('print.print-layout.header')
    <h2 style="text-align: center;">{{config('config.default_academic_session.name')}}</h2>
    <h2>{{trans('reception.enquiry').' '.trans('general.total_result_count',['count' => count($enquiries)])}}</h2>
    <table class="fancy-detail">
        <thead>
            <tr>
                <th>#</th>
                <th>{{trans('reception.enquirer')}}</th>
                <th>{{trans('reception.enquiry_type')}}</th>
                <th>{{trans('reception.enquiry_source')}}</th>
                <th>{{trans('reception.date_of_enquiry')}}</th>
                <th>{{trans('student.contact_number')}}</th>
                <th>{{trans('reception.no_of_students')}}</th>
                <th>{{trans('reception.enquiry_status')}}</th>
                <th>{{trans('reception.enquiry_remarks')}}</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($enquiries as $enquiry)
        		<tr>
                    <td>{{$enquiry->id}}</td>
                    <td>
                        @if($enquiry->father_name) {{trans('student.father_name').': '.$enquiry->father_name}} <br /> @endif
                        @if($enquiry->mother_name) {{trans('student.mother_name').': '.$enquiry->mother_name}} <br /> @endif
                        @if($enquiry->guardian_name) {{trans('student.guardian_name').': '.$enquiry->guardian_name}} <br /> @endif
                    </td>
                    <td>{{$enquiry->EnquiryType->name}}</td>
                    <td>{{$enquiry->EnquirySource->name}}</td>
                    <td>{{showDate($enquiry->date_of_enquiry)}}</td>
                    <td>
                        @if($enquiry->contact_number) {{trans('student.contact_number').': '.$enquiry->contact_number}} <br /> @endif
                        @if($enquiry->alternate_contact_number) {{trans('student.alternate_contact_number').': '.$enquiry->alternate_contact_number}} <br /> @endif
                        @if($enquiry->email) {{trans('student.email').': '.$enquiry->email}} <br /> @endif
                    </td>
                    <td>{{$enquiry->enquiry_details_count}}</td>
                    <td>{{trans('reception.enquiry_status_'.$enquiry->status)}}</td>
                    <td>{{$enquiry->remarks}}</td>
        		</tr>
        	@endforeach
        </tbody>
    </table>
@include('print.print-layout.footer')