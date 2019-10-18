@include('print.print-layout.header')
    <h2 style="text-align: center;">{{config('config.default_academic_session.name')}}</h2>
    <h2>{{trans('student.admission').' '.trans('general.total_result_count',['count' => count($students)])}}</h2>
    <table class="fancy-detail">
        <thead>
            <tr>
                @if(isColumnVisible('admission_number', $filter))
                    <th>{{trans('student.admission_number_short')}}</th>
                @endif
                @if(isColumnVisible('roll_number', $filter))
                    <th>{{trans('student.roll_number')}}</th>
                @endif
                <th>{{trans('student.first_name')}}</th>
                @if(isColumnVisible('middle_name', $filter))
                    <th>{{trans('student.middle_name')}}</th>
                @endif
                <th>{{trans('student.last_name')}}</th>
                @if(isColumnVisible('gender', $filter))
                    <th>{{trans('student.gender')}}</th>
                @endif
                @if(isColumnVisible('father_name', $filter))
                    <th>{{trans('student.father_name')}}</th>
                @endif
                @if(isColumnVisible('mother_name', $filter))
                    <th>{{trans('student.mother_name')}}</th>
                @endif
                @if(isColumnVisible('date_of_birth', $filter))
                    <th>{{trans('student.date_of_birth')}}</th>
                @endif
                @if(isColumnVisible('date_of_admission', $filter))
                    <th>{{trans('student.date_of_admission')}}</th>
                @endif
                @if(isColumnVisible('date_of_promotion', $filter))
                    <th>{{trans('student.date_of_promotion')}}</th>
                @endif
                @if(isColumnVisible('contact_number', $filter))
                    <th>{{trans('student.contact_number')}}</th>
                @endif
                    <th>{{trans('academic.course')}}</th>
                    <th>{{trans('academic.batch')}}</th>
                @if(isColumnVisible('nationality', $filter))
                    <th>{{trans('student.nationality')}}</th>
                @endif
                @if(isColumnVisible('blood_group', $filter))
                    <th>{{trans('misc.blood_group')}}</th>
                @endif
                @if(isColumnVisible('religion', $filter))
                    <th>{{trans('misc.religion')}}</th>
                @endif
                @if(isColumnVisible('caste', $filter))
                    <th>{{trans('misc.caste')}}</th>
                @endif
                @if(isColumnVisible('category', $filter))
                    <th>{{trans('misc.category')}}</th>
                @endif
                @if(isColumnVisible('unique_identification_number', $filter))
                    <th>{{trans('student.unique_identification_number')}}</th>
                @endif
                @if(isColumnVisible('father_contact_number_1', $filter))
                    <th>{{trans('student.father_contact_number_1')}}</th>
                @endif
                @if(isColumnVisible('mother_contact_number_1', $filter))
                    <th>{{trans('student.mother_contact_number_1')}}</th>
                @endif
                @if(isColumnVisible('emergency_contact_name', $filter))
                    <th>{{trans('student.emergency_contact_name')}}</th>
                @endif
                @if(isColumnVisible('emergency_contact_number', $filter))
                    <th>{{trans('student.emergency_contact_number')}}</th>
                @endif
                @if(isColumnVisible('present_address', $filter))
                    <th>{{trans('student.present_address')}}</th>
                @endif
                @if(isColumnVisible('permanent_address', $filter))
                    <th>{{trans('student.permanent_address')}}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    @if(isColumnVisible('admission_number', $filter))
                        <td>{{getStudentRecordForSession($student->StudentRecords, config('config.default_academic_session.id'), 'admission_number')}}</td>
                    @endif
                    @if(isColumnVisible('roll_number', $filter))
                        <td>{{getRollNumber(getStudentRecordForSession($student->StudentRecords, config('config.default_academic_session.id')))}}</td>
                    @endif
                    <td>{{$student->first_name}}</td>
                    @if(isColumnVisible('middle_name', $filter))
                        <td>{{$student->middle_name}}</td>
                    @endif
                    <td>{{$student->last_name}}</td>
                    @if(isColumnVisible('gender', $filter))
                        <td>{{trans('list.'.$student->gender)}}</td>
                    @endif
                    @if(isColumnVisible('father_name', $filter))
                        <td>{{optional($student->Parent)->father_name}}</td>
                    @endif
                    @if(isColumnVisible('mother_name', $filter))
                        <td>{{optional($student->Parent)->mother_name}}</td>
                    @endif
                    @if(isColumnVisible('date_of_birth', $filter))
                        <td>{{showDate($student->date_of_birth)}}</td>
                    @endif
                    @if(isColumnVisible('date_of_admission', $filter))
                        <td>{{getStudentRecordForSession($student->StudentRecords, config('config.default_academic_session.id'), 'date_of_admission')}}</td>
                    @endif
                    @if(isColumnVisible('date_of_promotion', $filter))
                        <td>
                            <td>{{getStudentRecordForSession($student->StudentRecords, config('config.default_academic_session.id'), 'date_of_entry')}}</td>
                        </td>
                    @endif
                    @if(isColumnVisible('contact_number', $filter))
                        <td>{{$student->contact_number}}</td>
                    @endif
                        <td>{{getStudentRecordForSession($student->StudentRecords, config('config.default_academic_session.id'), 'course')}}</td>
                        <td>{{getStudentRecordForSession($student->StudentRecords, config('config.default_academic_session.id'), 'batch')}}</td>
                    @if(isColumnVisible('nationality', $filter))
                        <td>{{$student->nationality}}</td>
                    @endif
                    @if(isColumnVisible('blood_group', $filter))
                        <td>{{$student->BloodGroup ? $student->BloodGroup->name : ''}}</td>
                    @endif
                    @if(isColumnVisible('religion', $filter))
                        <td>{{$student->Religion ? $student->Religion->name : ''}}</td>
                    @endif
                    @if(isColumnVisible('caste', $filter))
                        <td>{{$student->Caste ? $student->Caste->name : ''}}</td>
                    @endif
                    @if(isColumnVisible('category', $filter))
                        <td>{{$student->Category ? $student->Category->name : ''}}</td>
                    @endif
                    @if(isColumnVisible('unique_identification_number', $filter))
                        <td>{{$student->unique_identification_number}}</td>
                    @endif
                    @if(isColumnVisible('father_contact_number_1', $filter))
                        <td>{{$student->father_contact_number_1}}</td>
                    @endif
                    @if(isColumnVisible('mother_contact_number_1', $filter))
                        <td>{{$student->mother_contact_number_1}}</td>
                    @endif
                    @if(isColumnVisible('emergency_contact_name', $filter))
                        <td>{{$student->emergency_contact_name}}</td>
                    @endif
                    @if(isColumnVisible('emergency_contact_number', $filter))
                        <td>{{$student->emergency_contact_number}}</td>
                    @endif
                    @if(isColumnVisible('present_address', $filter))
                        <td>
                            {{$student->present_address_line_1}}
                            @if($student->present_address_line_2)
                                <span>, {{$student->present_address_line_2}}</span>
                            @endif
                            @if($student->present_city)
                                <span><br /> {{$student->present_city}}</span>
                            @endif
                            @if($student->present_state)
                                <span>, {{$student->present_state}}</span>
                            @endif
                            @if($student->present_zipcode)
                                <span>, {{$student->present_zipcode}}</span>
                            @endif
                            @if($student->present_country)
                                <span><br /> {{$student->present_country}}</span>
                            @endif
                        </td>
                    @endif
                    @if(isColumnVisible('permanent_address', $filter))
                        <td>
                            @if($student->same_as_present_address)
                                {{trans('student.same_as_present_address')}}
                            @else
                                {{$student->permanent_address_line_1}}
                                @if($student->permanent_address_line_2)
                                    <span>, {{$student->permanent_address_line_2}}</span>
                                @endif
                                @if($student->permanent_city)
                                    <span><br /> {{$student->permanent_city}}</span>
                                @endif
                                @if($student->permanent_state)
                                    <span>, {{$student->permanent_state}}</span>
                                @endif
                                @if($student->permanent_zipcode)
                                    <span>, {{$student->permanent_zipcode}}</span>
                                @endif
                                @if($student->permanent_country)
                                    <span><br /> {{$student->permanent_country}}</span>
                                @endif
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@include('print.print-layout.footer')