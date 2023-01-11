<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class testscontroller extends Controller
{
    public function getTestQuestions(Request $request , $subject_id){

        //has user registered for this exam
        $student_already_registered = DB::table('students')->where('users_id', Auth::user()->id)->where('subject_id',$subject_id)->exists();
        $has_takedn_exam = DB::table('result')->where('users_id',Auth::user()->id)
                            ->where('subject_id',$subject_id )->exists();


        if($has_takedn_exam){

            return \redirect()->route('main')->with('examSubmitted', 'You have already taken the exam');

        }

        // if user has not registered for this exam
        elseif(!$student_already_registered){
            return \redirect()->route('dashboard')->with('RegisterFirst', 'you have not already registered in exam');

        }
        else{
        // check if user has already taken the exam or not

         $question = DB::table('tests')->where('subject_id',$subject_id)->get();
         $exam_deadline = DB::table('subjects')->where('id',$subject_id )->value('exam_deadline');

         //if student start exam for the first time then return this


         $duration = DB::table('subjects')->where('id',$subject_id )->value('duration');

         //if something goes wrong, then user remaining_time
         $remaining_time = DB::table('students')->where('users_id',Auth::user()->id)
         ->where('subject_id',$subject_id)->value('remaining_time');

         if($remaining_time ==null){

            return view('test',['questions'=>$question , 'subject_id'=>$subject_id , 'exam_deadline'=>$exam_deadline, 'duration'=>$duration]);

         }else{

            return view('test',['questions'=>$question , 'subject_id'=>$subject_id , 'exam_deadline'=>$exam_deadline, 'duration'=>$remaining_time]);

         }


    }
}

        public function allResults(){
        // get all results that belongs to current logged in user
        $allResults = DB::table('result')->where('users_id',Auth::user()->id)->get();
        return view('table.allResults',['allResults'=>$allResults]);
        }



        public function allTests(){
            $subjects = DB::table('students')->where('users_id', Auth::user()->id)
                        ->join('subjects' ,'students.subject_id' , '=' ,'subjects.id' )->get();

                        return view('table.allTests',['subjects'=>$subjects]);



        }








    public function registerExam(Request $request,$subject_id){

        //check if user already registered
        $student_already_registered = DB::table('students')->where('users_id', Auth::user()->id)->where('subject_id',$subject_id)->exists();
        $has_takedn_exam = DB::table('result')->where('users_id',Auth::user()->id)
                                ->where('subject_id',$subject_id )->exists();

        if($has_takedn_exam){
            return \redirect()->route('dashboard')->with('hastakednexam', 'you have already taken this exam before');



        }


        elseif($student_already_registered){
            return \redirect()->route('dashboard')->with('alreadyRegisteredForExam', 'you have already registered in exam');


        }else{
                DB::table('students')->insert([
                'users_id'=>Auth::user()->id,
                'name'=>Auth::user()->name,
                'subject_id'=>$subject_id
                ]);
                return \redirect()->route('dashboard')->with('registeredForExam', 'you have successfully registered in exam');
            }
            }







    public function submitExam(Request $request){
        //$request contains the answers

        $answers = $request->all();
        //dd($answers);

        $points = 0;
        $percentage = 0;
        $totalQuestions = 2;

        $subjectId = $request->input('subjectId');

        foreach($answers as $questionId=>$userAnswer){
            //if the id is not a number then don't try to get an answer
            if(is_numeric($questionId)){
                $questionInfo = DB::table('tests')->where('id',$questionId)->get();

                //$question = [0=>['id'=>1 , name=>'' , correct_answer=>1]]
            $correctAnswer = $questionInfo[0]->correct_answer;

            if($correctAnswer == $userAnswer){
                //give user point
                $points++;

            }



            }

        }

        //calculate score
        $percentage = ($points/$totalQuestions)*100;
        //dd($percentage);

       $subject_name = DB::table('subjects')->where('id', $subjectId)->value('name');

        $id = Auth::user()->id;
        // insert score in the result table
        DB::table('result')->insert([
            'users_id'=>$id,
            'score'=>$percentage,
            'subject_Id'=>$subjectId,
            'subject_name'=>$subject_name
        ]);

        // remove student info from student table
        DB::table('students')->where('users_id',Auth::user()->id)->where('subject_id',$subjectId)->delete();

        //return to main page
        return redirect()->route('main')->with('examSubmitted','the exam has ben submitted successfuly , check ur profile ');

    }
public function sendRemainingTime($remaining_time,$subject_id){

    DB::table('students')->where("users_id",Auth::user()->id)
    ->where("subject_id",$subject_id)->update(["remaining_time"=>$remaining_time]);
    return " updated remaining_time";
}


}
