<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Task;
use Illuminate\Support\Str;
use App\Projet;
use App\Mail\loginMail;
use App\Mail\ClientMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UpdateData;
use Illuminate\Support\Facades\Notification;
use DB;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['resetpass']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('role','<>','chef de projet')->where('role','<>','admin')->where('role','<>','client')->latest()->paginate(8);
        return response()->json([
        "user" => $user
        ]);
    }
    // bloc chef de projet
    public function chef(){
        $chef= User::where('role','chef de projet')->first();
        return response()->json([
            "Chef" => $chef
        ]);
    }

  public function  ajouterChefDeProjet(Request $request){
            $this->validate($request,[
          'name' => 'required|string|min:3|max:20',
          'email' =>'required|string|email|unique:users',
          'password'=> 'required|string|min:6',
          'phone' => 'required|numeric|min:8'
           ]);
           $data = array(
            'login'    => $request->email,
            'password' => $request->password,
            'role'  => "chef de projet"
        );
        Mail::to($request->email)->send(new loginMail($data));
        return User::create([
            'name' => $request->name,
           'email'=> $request->email,
           'password'=> bcrypt($request->password),
           'phone'=> $request->phone,
           'role'=> "chef de projet"

       ]);


   return response()->json([
       "action"=>"Team Leader added"
   ]);
    }
    //bloc client
    public function  ajouterClient(Request $request){
        $validator= $this->validate($request,[
                'name' => 'required|string|min:3|max:20',
                'email' =>'required|string|email|unique:users',
                'password'=> 'required|string|min:6',
                'phone' => 'required|numeric|min:8'
                 ]);
                 $data = array(
                    'login'    => $request->email,
                    'password' => $request->password,
                    'name'  => $request->name
                );

                Mail::to($request->email)->send(new ClientMail($data));
              return User::create([
                  'name' => $request->name,
                 'email'=> $request->email,
                 'password'=> bcrypt($request->password),
                 'phone'=> $request->phone,
                 'role'=> "client"

             ]);


          }
          public function client(){
            $client=User::where('role','client')->latest()->paginate(8);
            return response()->json([
                "client"=>$client
            ]);
        }
       //bloc pour modification des données user connecté
    public function updateuserconnecté(Request $request){
        $data=$request->all();
        $id=Auth()->user()->id;
        $user=User::find($id);
        $user->email=$data['email'];
        $user->password=bcrypt($data['password']);
        $user->save();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'name' => 'required|string|min:3|max:20',
            'email' =>'required|string|email|unique:users',
            'password'=> 'required|string|min:6',
            'phone' => 'required',
            'role' => 'required'
             ]);
             $data = array(
                'login'    => $request->email,
                'password' => $request->password,
                'role'  => $request->role
            );
            Mail::to($request->email)->send(new loginMail($data));
             return User::create([
                'name' => $request->name,
                'email'=> $request->email,
                'password'=> bcrypt($request->password),
                'phone'=> $request->phone,
                'role'=> $request->role,
            ]);
          return response()->json([
              "action"=>"membre aded"
          ]);
        }
        public function clientprojet(){

            $client= User::where('role','client')->get();
            return response()->json([
               "client"=>$client
            ]);
        }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function Membreprojet()
    {
        $user= User::where('role','<>','admin')->where('role','<>','chef de projet')->where('role','<>','client')->get();
        return response()->json([
            "User"=> $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requestwhere
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $user=User::find($id);
         $this->validate($request,[
            'name' => 'string|min:3|max:20',
            'email' => 'string|email|unique:users,email,'.$user->id,
            'password'=> 'required|string|min:6',
            'phone' => 'required|min:8'
             ]);

          $data = $request->all();
          if ($user->role === 'chef de projet'){

            DB::table('users')->where('id',$id)->update(['name'=>$data['name'],'email'=>$data['email'],'password'=>bcrypt($data['password']),'phone'=>$data['phone']]);
               }
               else if($user->role === 'client'){
             DB::table('users')->where('id',$id)->update(['name'=>$data['name'],'email'=>$data['email'],'password'=>bcrypt($data['password']),'phone'=>$data['phone']]);
             DB::table('projets')->where('client_id',$id)->update(['owner'=>$data['name']]);
               }
               else {
              DB::table('users')->where('id',$id)->update(['name'=>$data['name'],'email'=>$data['email'],'password'=>bcrypt($data['password']),'phone'=>$data['phone'],'role'=>$data['role']]);
               }
               if (($data['password'] !== null) || ($data['email'] !== null)){
                $data = array(
                    'email' => $data['email'],
                    'password' => $data['password'],
                   );
                   $when = now()->addSeconds(10);
                   Notification::send( $user,(new UpdateData ($data))->delay($when));
               }
    }
    public function chefprojet(){
    $chef= User::where('role','chef de projet')->get();
    return response()->json([
        "Chef"=> $chef
    ]);

}
public function chefprojetwP(){
    $chef= User::where('role','chef de projet')->latest()->paginate(8);
    return response()->json([
        "Chef"=> $chef
    ]);

}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
       User::find($id)->delete();
    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

       if($request->email != null){
        $this->validate($request,[
            'email' =>'string|email|unique:users',
        ]);
       }
        if($request->password != null){
            $this->validate($request,[
                'password' => 'min:6',
            ]);
        }
            if($request->location != null){
                $this->validate($request,[
                    'location' => 'string|min:6|max:191',
                ]);
            }
                if($request->education != null){
                    $this->validate($request,[
                        'education' => 'string|min:3|max:191',
                    ]);
                    }
                    if($request->education != null){
                    $this->validate($request,[
                        'location' => 'string|min:6|max:191',
                    ]);
                    }

          if($request->photo !=null){


        $currentPhoto = $user->photo;


        if($request->photo != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);
            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
            //    @unlink($userPhoto);
            }
}

   if($request->email != null){
   $user->email=$request->email;
   $user->save();
  }
  if($request->password != null){
    $user->password=bcrypt($request->password);
    $user->save();
   }
   if($request->education != null){
    $user->education=$request->education;
    $user->save();
   }
   if($request->location != null){
    $user->location=$request->location;
    $user->save();
   }
   if($request->skills != null){
    $user->skills=$request->skills;
    $user->save();
   }
   if($request->photo != null){
    $user->photo=$request->photo;
    $user->save();
   }

        return ['message' => "Success"];
    }
else {

    if($request->email != null){
        $user->email=$request->email;
        $user->save();
       }
       if($request->password != null){
         $user->password=bcrypt($request->password);
         $user->save();
        }
        if($request->education != null){
         $user->education=$request->education;
         $user->save();
        }
        if($request->location != null){
         $user->location=$request->location;
         $user->save();
        }
        if($request->skills != null){
         $user->skills=$request->skills;
         $user->save();
        }
        if($request->photo != null){
         $user->photo=$request->photo;
         $user->save();
        }
}


}
public function Membrechefprojet()
    {
        return User::where('role','<>','admin')->latest()->paginate(100);
    }
    public function userprofile($id){
        $user = User::find($id);
        return response()->json([
        "user" => $user
        ]);
    }
 public function search(){

    if($search = \Request::get('s')){
        $chef = User::where('role','chef de projet')->where(function($query) use ($search){
            $query->where('role','chef de projet')->where('name','LIKE',"%$search%")->Orwhere('phone','LIKE',"%$search%")->Orwhere('email','LIKE',"%$search%")
            ;
        })->latest()->paginate(8);
      }
      return response()->json([
        "Chef"=>$chef
    ]);
 }
 public function searchc(){

   if($search = \Request::get('s')){
       $client = User::where('role','client')->where(function($query) use ($search){
           $query->where('name','LIKE',"%$search%")->Orwhere('phone','LIKE',"%$search%")->Orwhere('email','LIKE',"%$search%")
           ;
       })->latest()->paginate(8);
     }
     return response()->json([
       "client"=>$client
   ]);

}
public function searchm(){

    if($search = \Request::get('s')){
        $user = User::where('role','<>','chef de projet')->where('role','<>','admin')->where('role','<>','client')->where(function($query) use ($search){
            $query->where('name','LIKE',"%$search%")->Orwhere('phone','LIKE',"%$search%")->Orwhere('email','LIKE',"%$search%")
            ;
        })->latest()->paginate(8);
      }
      return response()->json([
        "user"=>$user
    ]);

 }

 public function searcht(){

    if($search = \Request::get('s')){
        $task = Task::where(function($query) use ($search){
            $query->where('text','LIKE',"%$search%")
            ;
        })->latest()->paginate(8);
      }
      return response()->json([
        "Tasks"=>$task
    ]);

 }
 public function resetpass(Request $request){
    $data = $request->all();
          $emailcount =User::where('email',$data['email'])->count();
        if($emailcount == '0'){
            return response()->json([
               "action" => "email Invalid"
            ]);
        }
        $userdetails= User::where('email',$data['email'])->first();
        $random_password=Str::random(8);
        $new_password= bcrypt($random_password);
        User::where('email',$data['email'])->update(['password'=>$new_password]);
        $email=$data['email'];
        $name=$userdetails->name;
        $message=[
            'email'=>$data['email'],
            'password'=>$random_password,
            'name'=>$name
        ];
        Mail::send('emails.forgetpassword', $message, function ($message)use($email) {
            $message->to($email)->subject('New Password - Project Managment');

        });
        return response()->json([
           "action" => "email send successfully"
        ]);
        
    }
    
 }
