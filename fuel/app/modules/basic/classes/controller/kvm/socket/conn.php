<?php 
namespace Basic;
class Controller_Kvm_Socket_Conn extends Controller_Kvm{
	public function before()
	{
	
		
		parent::before();
	}
	

public function action_index(){
	if($_POST){
		$val = \Validation::forge();
		$val->add_field('dev', 'Device id', 'required|min_length[1]|max_length[20]');
		$val->add_field('type', 'Input or output', 'required|min_length[1]|max_length[20]');
		if($val->run()){
			$kvm=Model_Device_Kvm::find()->where('deviceID',$val->validated('dev'))->get_one();
				
			if($kvm){


				
				$type=$val->validated('type');

				$out=Array('status'=>'ok','cables'=>Array(),'ports'=>Array());
					

				$sockets=$kvm->socket;
				
				foreach ($sockets as $socket){
					if($type==1 && $socket->type==2){
						array_push($out['ports'],Array('id'=>$socket->id));
						
					}
					if($type==2 && $socket->type==1){
						array_push($out['ports'],Array('id'=>$socket->id));
					
					}
				
				}
				
				
				
			


			$cabels=Model_Cable::find()->where('dev1',$kvm->deviceID)->where('type',3)->or_where('dev2',$kvm->deviceID)->where('type',3)->get();

 				//$out['ports']=$ports;
				
			foreach($cabels as $cab){
			array_push($out['cables'],Array(
			'id'=>$cab->id,
				'dev1'=>$cab->dev1,
				'port1'=>$cab->port1,
				'dev2'=>$cab->dev2,
				'port2'=>$cab->port2,
				'name1'=>$cab->name1,
				'name2'=>$cab->name2,
				'type'=>$cab->type

				));

 				}
					
					
					
					
 					
			}else{
				$out=Array('status'=>'nop');

		}
			
		echo json_encode($out);

			
	}
	}
		
}
}