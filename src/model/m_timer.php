<?php   //http://stackoverflow.com/questions/8310487/start-and-stop-a-timer-php  
namespace model;
 
class Timer {

   var $classname = "Timer";
   var $start     = 0;
   var $stop      = 0;
   var $elapsed   = 0;

   function __construct( $start = true ) {
      if ( $start )
         $this->start();
   }

   // Start the counting time
   function start() {
      $this->start = $_SERVER["REQUEST_TIME_FLOAT"];
   }

   // Stop counting time
   function stop() {
      $this->stop  = $this->_gettime();
      $this->elapsed = $this->_compute();
   }

   // Get Elapsed Time
   function elapsed() {
      if ( !$this->elapsed )
         $this->stop();

      return $this->elapsed;
   }

   // Resets Timer so it can be used again
   function reset() {
      $this->start   = 0;
      $this->stop    = 0;
      $this->elapsed = 0;
   }

   #### PRIVATE METHODS ####

  //Get Current Time
   function _gettime() {
      $mtime = microtime();
     $mtime = explode( " ", $mtime );
      return $mtime[1] + $mtime[0];
   }

  //Compute elapsed time
   function _compute() {
      return $this->stop - $this->start;
   }
}
