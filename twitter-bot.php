<?php
require_once 'inc/inc.php';
define('EMAIL_ID',    0);
define('EMAIL_ADRESS',1);


class mintwitter
{
  public  $user       = 'username';      /*@var TwitterUserName*/
  public  $pass       = 'password';       /*@var TwitterUserPass*/
  private $twitter;                       /*@var Twitter*/
  private $tinyurl;                       /*@var TinyUrl*/
  public  $dbUser     =  'root';          /*@var Database User*/
  public  $dbPass     =  'google';        /*@var Database Password*/
  public  $dbHost     =  'localhost';     /*@var Database Host*/
  public  $dbDatabase =  'twitter';       /*@var Database Name*/
  private $feed;                          /*@var Feed SimplePie*/
  private $db;                            /*@var Database*/
  private $logger;                        /*@var Logger*/
  public  $DBTable;                       /*@var Database Table Name*/
  private $emailValidator;                /*@var Email Adress Validator */
  private $dom;				  /*@var DOM OBJECT */
  private $faltDB;			  /*@var Flat DB Object */

  /**
  * @desc Connstructor 
  * @since 04.11.2009
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @licence GPL
  * @return bool 
  * @acces	public
  */
  public function __construct()
  {
    $this->tinyurl  = new tinyurl(); //@todo return @see returnItems 
    $this->twitter  = new Twitter($this->user, $this->pass );
    $this->feed     = new SimplePie();
    $this->dom      = new DomDocument('1.0'); 
    $this->logger   = new Logger('twitter');
    $this->emailValidator = new EmailAddressValidator();
    $this->flatDB         = new Flatfile();
    $this->flatDB->datadir    = 'cache/';
    $this->init();
  }
    
  /**
  * @desc InitProcess
  * @param Null
  * @return bool
  * @since 04.11.2009
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @version 0.1
  * @licence GPL 
  * @acces  private
  */
  private function init()
  {
    $this->logger->debug('INIT' );
    $this->feedUrls();
    $this->feed->init();
    $this->feed->handle_content_type();
  }

  /**
  * @desc SetFeedUrl For Fetching the Rss Content
  * @param array $urls
  * @return bool
  * @since 04.11.2009
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @licence GPL 
  * @acces	public
  */
  public function setFeedUrl($urls=array())
  {
    $this->feed->set_feed_url($url);
  }

  /**
  * @desc Set DB User, DB password and DB Host Above This is just for init
  * @since 08.11.2009
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @licence GPL
  * @return Bool 
  * @acces  public
  */
    public function initDB()
    {
      $this->db = new Database($this->dbHost, $this->dbUser,$this->dbPass, $this->dbDatabase);
      $this->db->connect();
    }
      
  /**
  * @desc This Method Sets the feedurls 
  * @since 04.11.2009
  * @author Faruk Yagbasan
  * @licence GPL 
  * @acces	private
  */
  private function feedUrls()
  {
   $this->feed->set_feed_url(array(
        'http://feeds.feedburner.com/Bludice',
        'http://snipplr.com/rss/',
        'http://feeds.feedburner.com/Tutorialzine',
        'http://feeds2.feedburner.com/ng-tech',
        'http://www.engadget.com/rss.xml',
        'http://www.phpdeveloper.org/feed',
        'http://mark.koli.ch/atom.xml',
        'http://www.fromzerotoseo.com/feed/'
        
        ));
  }

  /**
  * @desc Returns the remaining twitter Limit 
  * @since 04.11.2009
  * @author Faruk Yagbasan
  * @licence GPL
  * @return INT 
  * @acces  public
  */
  public function getTwitterLimit()
  {
    $twittLimit = $this->twitter->getRateLimitStatus();
    $limit      = $twittLimit[ 'remaining_hits' ];
    $timeTill   = $twittLimit[ 'reset_time' ];
    $this->logger->debug('Limit' . $limit . "\n Am " . date('d-M-Y,H:i ', $timeTill ));
  }

  /**
  * @desc Returns the Item to Twitt 
  * @since 04.11.2009
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @licence GPL
  * @return Array() 
  * @acces  private
  */
  public function returnItems()
  {        
    $items = $this->feed->get_items();

    for ($i=0; $i < count($items); $i++)
    {
      $content                    = strip_tags($items[$i]->get_content());
      $search                     = explode(" ",$items[$i]->get_title());
      $result[$i]["content"]      = cutString(normalizeString($content));
      $result[$i]["search"]       = trim("#".$search[rand(0,5)]);
      $result[$i]["title"]        = cutString(normalizeString($items[$i]->get_title()));
      $result[$i]["url"]          = $this->tinyurl->get($items[$i]->get_link());
      $result[$i]["status"]       = $result[$i]["content"]." ".$result[$i]["search"];
            
      strlen($result[$i]["status"]) <= 140 && strlen($result[$i]["status"]) > 130 ?
      $result[$i]["status"]          = $result[$i]["content"]." ".$result[$i]["search"] :
      $this->logger->error('Couldnt add item to result Message Was To Big ' );
    }
        
        return $result;
  }

  /**
  * @desc Adds The tweets To DB
  * @since 04.11.2009
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @licence GPL
  * @return Bool 
  * @acces	public
  */
  public function addTweetsToDB()
  {
    $this->logger->debug('adding Tweets To DB' );	
    $this->initDB();
    foreach ($this->returnItems() as $item )
    {
      $tweets['tweet']    = $item["status"]; //remember this is the table field tweet
      !$this->db->query_insert($this->DBTable, $tweets )  ?
      $this->logger->error('ERROR on Insert To DB' ):
      $this->logger->error('Something is Wrong');
    }
  }
  
  /**
  * @desc If you dont have a db Use This Method To Update Your Twitter Status
  * @since 04.11.2009
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @licence GPL
  * @return Bool 
  * @acces	public
  */
  public function updateStatusWithoutDB()
  {
    $this->logger->debug('Updating Status Without  DB' );
    $this->updateProfileColors();

    foreach ($this->returnItems() as $item )
    {
      $this->twitter->updateStatus($item['status'])  ?
      $this->logger->debug('updating status' )   :
      $this->logger->error('Something is Wrong' . $status );
    }
  }
  

  /*
  * @desc Update Your Status on Twitter.com It gets The Status from DB
  * So if you want  Update Your Status Without DB Use The Method updateStatusWithoutDB
  * @param null
  * @since 04.11.2009
  * @acces public 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return Array()
  */
  public function updateStatus($limit = 150)
  {
    $this->initDB();
    $sql    = "SELECT tweet FROM tweets ORDER BY RAND() LIMIT {$limit}";
    $result 	  = $this->db->fetch_all_array($sql);
    $this->logger->debug('' . $sql );
    $this->updateProfileColors();
    $this->logger->debug('Colors Updatet Succefull');
  
  for ($i = 0; $i<count($result ); $i++ )
  { 
      try
      {
        $this->twitter->updateStatus($result[$i]['tweet']) ?
        $this->logger->debug('updating status' )    :
        $this->logger->error('Couldnt Update status' );
      }
      catch(Exception $e )
      {
        $this->logger->error(''.$e->getMessage());
      }
    }
    unset($result);
  }

 /**
  * @desc Search  Twitter.com For a given Term an return Results in a Array
  * @param String Terms
  * @since 09.11.2009
  * @acces public 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return Array()
  */
  public function searchFor($terms)
  {
    if (!$xml = simplexml_load_file('http://search.twitter.com/search.atom?q='.urlencode      ($terms)))
    {
       throw new RuntimeException('Unable to load or parse search results feed');
    }
    if (!count($entries = $xml->entry))
    {
        throw new RuntimeException('No entry found');
    }
    for($i=0;$i<count($entries);$i++)
    {
       $retweet[$i] = $entries[$i]->title;
    }
  return $retweet;
  }
  
  
  /**
  * @desc Send Direct Message To Your Followers
  * @param String Text
  * @since 04.11.2009
  * @acces public 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return Bool
  */
  public function sendDirectMessageToFollowers($text)
  {
    $followerIds = $this->twitter->getFollowerIds();

    foreach($followerIds as $id)
    {
      try
      {
          $this->twitter->sendDirectMessage($id,$text);
      }
      catch (Exception $e) 
      {
          $this->logger->debug("Couldnt Send Message To:".$id);
      }
    }

  }
  
  
  /**
  * @desc Searches Twitter.com For Trends and gets the tweets and Updates Your Status                
  * @param null 
  * @since 04.11.2009
  * @acces Public 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return Null
  */
  public function tweetTrends($addTrendsToDB=true)
  {
    $trends = $this->trends();
    if(empty($trends))return false;  
    $messages = $this->searchFor($trends[rand(0,9)]);

     for($i=0;$i<count($messages);$i++)
     {
      try
      {
      if($addTrendsToDB)
      {
          $tweets['tweet']  = $messages[$i]; //remember this tweet is the table field in the database
         !$this->db->query_insert($this->DBTable, $tweets )  ?
          $this->logger->error('ERROR on Insert Trends To DB' ):
          $this->logger->error('Something is Wrong');
        }
      
        $this->twitter->updateStatus(cutString($messages[$i],140)) ?
        $this->logger->debug('updating status' )   :
        $this->logger->error('Couldnt Update status' );
      }
      catch(Exception $e)
      {
        $this->logger->error(''.$e->getMessage().'Cant Update Status :\n'.$messages[$i]);
      }
     }
    unset($messages);
  }


  private function makeEmailAdressInEmailFileUnique()
  {
    $this->dom->formatOutput = true;
    if(file_exists("cache/emails.txt"))
    	$fileContent  = file_get_contents("cache/emails.txt");
    
    $pattern 	  = '#[a-z0-9\-_]?[a-z0-9.\-_]+[a-z0-9\-_]?@[a-z.-]+\.[a-z]{2,}#i';
    preg_match_all($pattern,$fileContent,$matches);
    
    $uniqueEmails = array_unique($matches[0]);
    $Emailadress  = $this->dom->appendChild($this->dom->createElement('emails')); 
    $array_iter   = new RecursiveArrayIterator($uniqueEmails);
    $iter_iter    = new RecursiveIteratorIterator($array_iter);
    
    foreach($iter_iter as $email)
    {
     $adress = $Emailadress->appendChild($this->dom->createElement('email'));
     $adress->appendChild($this->dom->createTextNode($email));
    }
    
    $filename = $this->dom->saveXML(); // put string in email 
    $this->dom->save($filename); // save as file 
  }


  /**
  * @desc Searches Twitter.com For Email Adresses and Writes them to a File
  * @param String Filename Defaul emails.txt
  * @since 08.11.2009
  * @acces public 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return  bool
  **/
  public function writeEmailsToXML($filename="emails.xml")
  {
      $emails[]          = getEmails($max_pages = 3); // create array of email adresses
      $array_iter        = new RecursiveArrayIterator($emails);
      $iter_iter         = new RecursiveIteratorIterator($array_iter);
      
      foreach($iter_iter as $email) 
      { 
        
        !empty($email) && $this->emailValidator->check_email_address($email) ?
         $this->insertEmailToFlat($email) :
         $this->logger->error('ERROR on Write Email : '.$email.' To File' );
      }
	
       $this->makeEmailAdressInEmailFileUnique($filename);
    
  }

  /**
  * @desc Searches Twitter.com For Email Adresses and Returns Them
  * @param null
  * @since 08.11.2009
  * @acces public 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return  Null
  */
  public function writeEmailsToDB()
  {
    $this->initDB();
    $emails[]       =    getEmails($max_pages = 3); // no need to put dublicates out db field is unique  
    $array_iter     =    new RecursiveArrayIterator($emails);
    $iter_iter      =    new RecursiveIteratorIterator($array_iter);
    
    foreach($iter_iter as $email)
    {
        //hmm .. this part of code is genius written by faruk 
      !empty($email) ?  //we have something 
      $this->emailValidator->check_email_address($email) : // now validate Emailadress
      $adresse['email']	  = $email; // finaly we have a valid adress so put in to the db
      !$this->db->query_insert('emails', $adresse)  ?
      $this->logger->error('ERROR on Insert Email : '.$email.' To DB' ):
      $this->logger->error('Something is Wrong');
    }
    
    //some clen up
    unset($iter_iter);
    unset($array_iter);
    unset($emails);
  
  }

  /**
  * @desc Searches Twitter.com For Trends and Returns Them
  * @param null
  * @since 04.11.2009
  * @acces private 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return Array()
  */
  public function trends()
  {
    $contents   = file_get_contents("http://search.twitter.com/trends.json");
    $json       = json_decode($contents);
    
    foreach ($json->trends as $trend) 
    {
      $trends[] = $trend->name;
    }
    
    return $trends;
  }
  
  
  /**
  * @desc Update Your Profile Color On Twitter Randomly
  * @param null
  * @since 04.11.2009
  * @acces public 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return void
  */
  public function updateProfileColors()
  {
  $c1  = getRandomColor();  $c2  = getRandomColor();
  $c3  = getRandomColor();  $c4  = getRandomColor();
  $c5  = getRandomColor();
  $this->twitter->updateProfileColors($c1,$c2,$c3,$c4,$c5);
  }

  /**
  * @desc Returns the How may Feeds there are. 
  * @param null
  * @since 04.11.2009
  * @acces public 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return INT
  */
  public function getFeedCount()
  {
    return $this->feed->get_item_quantity();
  }
  
  /**
  * @desc Ends Twitter Session
  * @param null
  * @since 04.11.2009
  * @acces private 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return Array()
  */
  public function __destruct()
  {
    $this->twitter->endSession();
  }
  
  /**
  * @desc Follow Your Followers
  * @param null
  * @since 04.11.2009
  * @acces public 
  * @author  Faruk Yagbasan <wyde16@gmail.com>
  * @return void
  */
  public function followFollowers()
  {
    foreach ($this->twitter->getFollowers() as $follower)
    {
      if ($this->twitter->existsFriendship($this->user, $follower['screen_name'])) //If You  Follow this user
          continue;    //no need to follow now;
      try
      {
        $this->twitter->createFriendship($follower['screen_name'], true); // If you dont Follow Followit now
        $this->logger->debug('Following new follower: '.$follower['screen_name']);
      }
      catch (Exception $e)
      {
        $this->logger->debug("Skipping:".$follower['screen_name']." ".$e->getMessage());
      }
      
    }
    
  }
  
  private function insertEmailToFlat($adress)
  {
      $newpost[EMAIL_ID]; 
      $newpost[EMAIL_ADRESS] = $adress;
      return $newId = $this->flatDB->insertWithAutoId('emails.txt',EMAIL_ID, $newpost);
  }
  
  
/*END Twitter-Bot*/
  
}
$twitt = new mintwitter();
//$twitt->followFollowers();
//$twitt->tweetTrends();
//$twitt->addTweetsToDB();
//$twitt ->updateStatus();
//$twitt->writeEmailsToXML();
