<?php

namespace App\Http\Controllers;

use App\Models\book_issue;
use App\Http\Requests\Storebook_issueRequest;
use App\Http\Requests\Updatebook_issueRequest;
use App\Models\auther;
use App\Models\book;
use App\Models\settings;
use App\Models\student;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdatestudentRequest;
use Illuminate\Support\Facades\DB;

class BookIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('book.issueBooks', [
            'books' => book_issue::Paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('book.issueBook_add', [
            'students' => student::latest()->get(),
            'books' => book::where('status', 'Y')->get(),

            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storebook_issueRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storebook_issueRequest $request)
    {
        $issue_date = date('Y-m-d');
        $return_date = date('Y-m-d', strtotime("+" . (settings::latest()->first()->return_days) . " days"));
        $data = book_issue::create($request->validated() + [
            'student_id' => $request->student_id,
            'book_id' => $request->book_id,
            'issue_date' => $issue_date,
            'return_date' => $return_date,
            'issue_status' => 'N',
          //  dd($request->name),
        ]);
        $studentMail = DB::table('students')->where('id', $request->student_id)->pluck('email');
        $studentBook = DB::table('books')->where('id', $request->book_id)->pluck('name')->first();
        $name = Auth::user()->name;
       // dd($name);
        $details = [
            'title' => 'Book Issue',
            'body' => sprintf("This is to inform you that you have borrowed the %s and return date is %s. Please note you have to return the book on
             time, or you will be fined for late submissions.", $studentBook, $return_date),
            'admin' => $name,
        ];
        //dd($details);
        
        Mail::to($studentMail)->send(new \App\Mail\UserEmail($details));
        $data->save();
        $book = book::find($request->book_id);
        $book->status = 'N';
        $book->save();
        
        return redirect()->route('book_issued');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // calculate the total fine  (total days * fine per day)
        $book = book_issue::where('id',$id)->get()->first();
        $first_date = date_create(date('Y-m-d'));
        $last_date = date_create($book->return_date);
        $diff = date_diff($first_date, $last_date);
        $fine = (settings::latest()->first()->fine * $diff->format('%a'));
        return view('book.issueBook_edit', [
            'book' => $book,
            'fine' => $fine,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatebook_issueRequest  $request
     * @param  \App\Models\book_issue  $book_issue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $book = book_issue::find($id);
        $book->issue_status = 'Y';
        $book->return_day = now();
        $book->save();
        $bookk = book::find($book->book_id);
        $bookk->status= 'Y';
        $bookk->save();
        return redirect()->route('book_issued');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\book_issue  $book_issue
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       try {
        //code...
        book_issue::find($id)->delete();
        return redirect()->route('book_issued');
       } catch (\Throwable $th) {
        throw $th;
        return redirect()->route('book_issued');
       }
        
        
        
    }
}
