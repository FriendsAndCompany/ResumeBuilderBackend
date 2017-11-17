<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resume;
use App\Student;
use App\Project;
use App\Degree;
use App\Position;
use App\Internship;
use App\Award;
use App\Hobby;
use App\Template;
use JWTAuth;


use Illuminate\Support\Facades\DB;


class ResumeController extends Controller
{


    protected $fillable = ['name','email','address','dob'];


    public function store(Request $request){
      if(! $user = JWTAuth::parseToken()->authenticate()){
        return response()->json(['message' => 'User not Found'] , 404);
      }

        $data = $request->only('_id','data');
        $resume = $data['data']['resume'];
         $user_id = $data['_id'];




         $name = $resume['info']['name'];
         $email = $resume['info']['email'];
         $address = $resume['info']['address'];
         $dob = $resume['info']['dob'];



         $user = new Resume;
         $user->{'data.resume.info.name'} = $name;
         $user->{'data.resume.info.email'} = $email;
         $user->{'data.resume.info.address'} = $address;
         $user->{'data.resume.info.dob'} = $dob;

         $templates = $data['data']['template'];
         $user->{'data.template'} = $this->getTemplates($templates);

        $degrees = $resume['degree'];
        $user->{'data.resume.degree'} = $this->getDegrees($degrees);

        $internships = $resume['internship'];
        $user->{'data.resume.internship'} = $this->getInternship($internships);

        $projects = $resume['project'];
        $user->{'data.resume.projects'} = $this->getProjects($projects);

        $positions = $resume['position'];
        $user->{'data.resume.position'} = $this->getPositions($positions);


        $awards = $resume['award'];
        $user->{'data.resume.award'} = $this->getAwards($awards);

        $hobbies = $resume['hobby'];
        $user->{'data.resume.hobby'} = $this->getHobby($hobbies);

        $user->save();

        //return view('welcome');
    }


    public function index(){
    //$resume =  Resume::all();
    $user = JWTAuth::parseToken()->toUser()->value('email');
    $resume= Resume::where('data.resume.info.email', '='  , $user)->project(['_id' => 0])->get();
    return $resume;

    }

    public function destory()
    {
      //$resume =  Resume::all();
      $user = JWTAuth::parseToken()->toUser()->value('email');
      $resume= Resume::where('data.resume.info.email', '='  , $user)->delete();

    }


    public function update(Request $request){

      if(!$user = JWTAuth::parseToken()->authenticate()){
        return response()->json(['message' => 'User not Found'] , 404);
      }

        $user = JWTAuth::parseToken()->toUser()->value('email');
         Resume::where('data.resume.info.email', '='  , $user)->update($request->all());




    }

    public function userTemplates(Request $request){
      if(!$user = JWTAuth::parseToken()->authenticate()){
        return response()->json(['message' => 'User not Found'] , 404);
      }
      $user = JWTAuth::parseToken()->toUser()->value('email');
      $resume = Resume::where('data.resume.info.email', '='  , $user)->project(['_id' => 0])->get();
      $template = $resume['0']['data']['template'];
      return $template;

    }

    protected function getTemplates($templates){
      $userTemplates = [];
      if (count($templates) > 0) {
          foreach ($templates as $template) {
              $userTemplates[] = new Template(
                  $template['id'],
                  $template['name']

              );
          }
    }
    return $userTemplates;


    }
    protected function getProjects($projects)
    {
          $userProjects = [];
          if (count($projects) > 0) {
              foreach ($projects as $project) {
                  $userProjects[] = new Project(
                      $project['name'],
                      $project['description'],
                      $project['start'],
                      $project['end'],
                      $project['team_size'],
                      $project['guide']
                  );
              }
        }
        return $userProjects;
    }

    protected function getInternship($internships)
    {
          $userInternship = [];
          if (count($internships) > 0) {
              foreach ($internships as $internship) {
                  $userInternship[] = new Internship(
                      $internship['name'],
                      $internship['description'],
                      $internship['start'],
                      $internship['end'],
                      $internship['team_size'],
                      $internship['guide']
                  );
              }
        }
        return $userInternship;
    }

    protected function getDegrees($degrees)
    {
        $userDegrees = [];

        if(count($degrees) > 0){
          foreach($degrees as $degree){
              $userDegrees[] = new Degree(
                  $degree['name'],
                  $degree['institute'],
                  $degree['start_year'],
                  $degree['end_year'],
                  $degree['score']
              );
          }
        }
        return $userDegrees;
    }

    protected function getPositions($positions)
    {
          $userPositions = [];

          if(count($positions) > 0){
            foreach($positions as $position){
              $userPositions[] = new Position(
                $position['name']
              );
            }
          }
          return $userPositions;
    }

    protected function getAwards($awards)
    {
          $userAwards = [];

          if(count($awards) > 0){
            foreach($awards as $award){
              $userAwards[] = new Award(
                $award['name']
              );
            }
          }
          return $userAwards;
    }

    protected function getHobby($hobbies)
    {
          $userhobbies = [];

          if(count($hobbies) > 0){
            foreach($hobbies as $hobby){
              $userhobbies[] = new Hobby(
                $hobby['name']
              );
            }
          }
          return $userhobbies;
    }
}
