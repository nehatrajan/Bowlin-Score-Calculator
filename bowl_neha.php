<?php
/*
 
Written by: Neha Thiyagarajan(nehathiyagarajan@gmail.com)
Date: 08-29-2014
 
Run script from command line
Input format interactive as entered by the user for each frame
 
*/

class game{


	private $frames = array();
	private $frame_score = array();
	private $score = 0;
	private $total_score = 0;
	private $spare_count =0;
	private $strike_count = 0;
	public $extra = false;
	public $turkey = array();

    /* This functinon will store the scores of each frame in frames[10][2] array */
	function frame($frame_id , $ball_id, $pins)
	{

		$this->frames[$frame_id][$ball_id] = $pins;
		/* Below condition handles the special frame (10), if spare or strike is thrown */
		if($this->frames[$frame_id][1] == 10 && $frame_id<11)
		{
			echo "\nWow!! You hit a Strike\n";
			$this->strike_count++;
			if($this->strike_count >=2)
			{
				echo "Turkey is bowled\n";
				echo "<')__V\n";
				echo " (___)\n";
				echo "  | |\n";
				echo "  x x\n";

				$this->turkey[$frame_id]= 1;


			}
		}

		if($ball_id == 2 || $this->extra == true)
			$this->frame_score($frame_id);
	}

	/*This function will calculate and store the scores for each frame in frame_score[10] array */
	function frame_score($frame_id)
	{
		if($this->spare_count == 1 && $frame_id <=11)
		{
			$this->spare_score($frame_id);

			$this->spare_count = 0;
		}
		if($this->strike_count !=0 && $frame_id <=11)
		{
			
			$this->strike_score($frame_id);		
		}
		if($frame_id<11) {

			$this->score = $this->frames[$frame_id][1] + $this->frames[$frame_id][2];

			if($this->score == 10)
			{
				$this->spare_count = 1;
				echo "That is a Spare\n";
			}
			if($this->spare_count ==0)
			{
				$this->frame_score[$frame_id] = $this->score;
				echo "\nFrame Score ".$frame_id.":".$this->frame_score[$frame_id]."\n";
				$this->total_score($frame_id);
			}
		}
	}

    /*This functino will calculate the total score so far of the game and stores in total_socre variable */
	function total_score($frame_id)
	{

		$this->total_score = $this->total_score + $this->frame_score[$frame_id];
		echo "\n"."Total Score is : ".$this->total_score."\n";
	}

     /* This function handles the scoring of the spare condition */
	function spare_score($frame_id)
	{
		$temp = $frame_id-1;
		$spare_score = 10 + $this->frames[$frame_id][1];
		$this->frame_score[$frame_id-1] = $spare_score;
		$this->total_score($frame_id-1);
	}

     /* This function handles the scoring of the strike condition */
	function strike_score($frame_id)
	{

		do {if($this->strike_count >= 3)
			{
				$temp = $frame_id-$this->strike_count;
				$strike_score = 30;
				$this->frame_score[$temp] = $strike_score;
				echo "\nFrame Score ".$temp.":".$this->frame_score[$temp];
				$this->total_score($temp);
				$this->strike_count--;

			}

			if($this->strike_count ==2)
			{	$temp = $frame_id-$this->strike_count;
				$strike_score = 20 + $this->frames[$frame_id][1];
				$this->frame_score[$temp] = $strike_score;
				echo "\nFrame Score ".$temp.":".$this->frame_score[$temp];
				$this->total_score($temp);
				$this->strike_count--;

			}
			if($this->strike_count == 1)
			{
				$temp = $frame_id-1;
				$strike_score = 10 + $this->frames[$frame_id][1] + $this->frames[$frame_id][2];
				$this->frame_score[$frame_id-1] = $strike_score;
				echo "\nFrame Score ".$temp.":".$this->frame_score[$temp];
				$this->total_score($temp);
				$this->strike_count--;
			}

		} while($this->strike_count >0);

	}

        function display(){
          $dscore = "|";
          echo "========================================================================\n";
                echo "|FRAME1|FRAME2|FRAME3|FRAME4|FRAME5|FRAME6|FRAME7|FRAME8|FRAME9|FRAME10|\n";
                for($i=1;$i<=10;$i++) { 
                	if($this->turkey[$i] == 1)
                		$dscore = $dscore."  ".TK."  |"; 

                	else	
                   		$dscore = $dscore."  ".sprintf("%02s", $this->frame_score[$i])."  |"; 
                } 
               echo $dscore;
                //echo "|   |  |   |  |   |  |   |  |  |   |   |  |   |  |   |  |   |  |   |   |\n";
                echo "\n========================================================================\n";
                echo "*TK = Turkey";
        }

}


$game = new game;
$set = 10;
$ball_count = 2;
$pins = 0;


for($frame = 1; $frame <= $set ; $frame++)
{

	for($ball = 1; $ball<=$ball_count ; $ball++)
	{
		
		echo "Enter the pins knocked down in Frame ".$frame." and ball ".$ball." :";
		$pins = fgets(STDIN);
		
		if($ball == 1)
			$ball1_pins = $pins;
		if($pins > 10)
		{
			echo "\nPins cannot exceed 10\n";
			$ball--;
			$pins = 0;
			continue;
		}
		if($ball == 2 && $pins > 10-$ball1_pins && $frame<=10)
		{
			echo "\nPins cannot exceed ".(10-$ball1_pins)."\n";
			$ball--;
			$pins = 0;
			continue;
		}
		$game->frame($frame,$ball,$pins);
	//	echo "\nFrame Score ".$frame_id.":".$this->frame_score[$frame_id]."\n";
	//	echo "\n"."Total Score is : ".$this->total_score."\n";


		if($pins == 10 && $frame <10)
			break;
		if($frame==10 && $ball==2 && ($ball1_pins+pins == 10)) {
			
			$set = $set+1;
			$ball_count = 1;
			$game->extra = true;
			break;
		}
		if($frame == 10  && $pins == 10)
		{
			$set = $set+1;
			
			break;
		}
		
	}
}


$game->display();

?>
