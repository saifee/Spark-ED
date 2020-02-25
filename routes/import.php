<?php

Route::get('/secret/data/importer/books', function () {
    return view('secret-data-importer');
});
Route::post('/secret/data/importer/books', function (\Illuminate\Http\Request $request) {
    // cleanup
    if ($request->has('truncate') && $request->input('truncate') == 1) {
        \DB::table('books')->truncate();
    }

    // fetch file as array
    $csv = array_map('str_getcsv', file($request->file('csv_file')->getRealPath()));

    $flag = true;
    // loop
    \App\Models\Library\Book::unguard();
    foreach ($csv as $line) {
        if($flag) { $flag = false; continue; }
        if (blank($line[1])) { continue; }
        // create book
        $newBook = \App\Models\Library\Book::updateOrCreate(['title' => trim($line[1])], [
            'isbn_number' => $line[2],
            'book_author_id' => \App\Models\Configuration\Library\BookAuthor::firstOrCreate(['name'=>isset($line[3]) && filled($line[3]) ? $line[3] : 'Unknown'])->id,
            'book_language_id' => \App\Models\Configuration\Library\BookLanguage::firstOrCreate(['name'=>isset($line[4]) && filled($line[4]) ? $line[4] : 'Unknown'])->id,
            'book_topic_id' => \App\Models\Configuration\Library\BookTopic::firstOrCreate(['name'=>isset($line[8]) && filled($line[8]) ? $line[8] : 'Unknown'])->id,
            'book_publisher_id' => \App\Models\Configuration\Library\BookPublisher::firstOrCreate(['name'=>isset($line[7]) && filled($line[7]) ? $line[7] : 'Unknown'])->id,
            'page' => isset($line[5]) && filled($line[5]) && is_numeric($line[5]) ? $line[5] : 0,
            'price' => isset($line[6]) && filled($line[6]) ? $line[6] : 0,
            'uuid' => \Illuminate\Support\Str::uuid(),
            'options' => array(),
        ]);
        if (filled($newBook->bookPosts)) { continue; }
        $book_post = \App\Models\Library\BookPost::create([
            'book_id' => $newBook->id,
            'date_of_addition' => '2010-01-01',
            'quantity' => 1,
            'remarks' => '',
            'options' => array(),
        ]);
        \App\Models\Library\BookPostDetail::create([
            'book_post_id' => $book_post->id,
            'number' => 1,
            'location' => '',
            'options' => array()
        ]);
    }
    \App\Models\Library\Book::reguard();
    return "done.";
});

Route::get('/secret/data/importer/students', function () {
    return view('secret-data-importer');
});
Route::post('/secret/data/importer/students', function (\Illuminate\Http\Request $request) {
    // cleanup
    if ($request->has('truncate') && $request->input('truncate') == 1) {
        \DB::table('students')->truncate();
    }

    // fetch file as array
    $csv = array_map('str_getcsv', file($request->file('csv_file')->getRealPath()));

    $flag = true;
    // loop
    \App\Models\Student\Student::unguard();
    foreach ($csv as $line) {
        if($flag) { $flag = false; continue; }
        // create student
        $newStudent = \App\Models\Student\Student::firstOrCreate(['first_name' => $line[1]], [
            'student_parent_id' => \App\Models\Student\StudentParent::firstOrCreate(['first_guardian_name'=>$line[2]],[
              'first_guardian_relation' => 'father',
              'first_guardian_contact_number_1' => $line[13] . '; ' . $line[14],
              'first_guardian_contact_number_2' => $line[15] . '; ' . $line[16],
              'second_guardian_name' => 'Not Available',
              'second_guardian_relation' => 'mother',
              'options' => array(),
            ])->id,
            'date_of_birth' => toDate($line[3]),
            'gender' => $line[4] === 'F' ? 'female' : 'male',
            'contact_number' => substr($line[13] . '; ' . $line[14] . '; ' . $line[15] . '; ' . $line[16], 0, 20),
            'email' => substr($line[17] . '; ' . $line[18], 0, 50),
            'nationality' => substr($line[5], 0, 20),
            'religion_id' => \App\Models\Configuration\Misc\Religion::firstOrCreate(['name'=>$line[6]])->id,
            'present_address_line_1' => $line[7],
            'uuid' => \Illuminate\Support\Str::uuid(),
            'options' => array(),
        ]);
        \App\Models\Student\Registration::create([
          'student_id' => $newStudent->id,
          'course_id' => \App\Models\Academic\Course::firstOrCreate(['name'=>$line[9]])->id,
          'date_of_registration' => toDate($line[21]),
          'registration_fee' => 0,
          'status' => 'pending',
          'options' => array(),
        ]);
    }
    \App\Models\Student\Student::reguard();
    return "done.";
});
