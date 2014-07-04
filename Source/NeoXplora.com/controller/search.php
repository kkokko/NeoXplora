<?php	
namespace NeoX\Controller;

require_once APP_DIR . "/app/system/Object.php";
class TSearch extends \SkyCore\TObject {
	
    public function index() {
	
		$this->template->addStyle("style/search_results.css");
		$this->template->addScript("js/search.js");
		$this->template->load("index", "search");
		$this->template->pageTitle = "Search";
		$this->template->hide_right_box = true;
		
		// handle search preRequest
		if(isset($_GET['q']) && $_GET['q']!=""){
			
			$this->template->preRequest = true;
			$this->getSearchResults();
		}
		$this->template->extra_classes = "searchPageContainer";
		$this->template->render();
    }
  
	public function getSearchResults(){
		if(isset($_REQUEST['q']) && $_REQUEST['q']!="") {
			
			$sQuery=$_REQUEST['q'];
			$pageOffset = 0;
			if(isset($_REQUEST['page'])){
				$pageOffset = intval($_REQUEST['page'])-1;
			}
			$result = null;
			$serverException = false;
			try {
				$server = $this->Delphi();
				$result = $server->Search($sQuery , $pageOffset*10);
				
			} catch(Exception $e) {
				$serverException = true;
				
				print '<pre>';
				var_dump($e);
				print '</pre>';
				/***************/
			}
			
			if($serverException){
				$this->template->errorMessage = true;
				$this->template->message = 'There was an error performing the request.';
			}
			if($result!=null){
				$this->template->q = $sQuery;
				$overAllCount = $result->GetProperty("PageCount");
				$count = $result->GetProperty("Pages")->Count();
				if($overAllCount>0){
					// search stats
					$resultsPerPage = 10;
					$shownResults = ($pageOffset*$resultsPerPage+1).' - ';
					$shownResults .= (($pageOffset+1)*$resultsPerPage<$overAllCount)?($pageOffset+1)*$resultsPerPage:$overAllCount;
					$this->template->message = 'Showing '.$shownResults.' results of '.$overAllCount.' found.';
					$searchResults = array();
					// search results
					for($i=0;$i<$count;$i++){
						$pageItem = $result->GetProperty("Pages")->Item($i);
						$searchResults[] = array(
							"Id"=>$pageItem->GetProperty("Id"),
							"Link"=>$pageItem->GetProperty("Link"),
							"Title"=>$pageItem->GetProperty("Title"),
							"Body"=>$pageItem->GetProperty("Body")
						);
					}
					$this->template->searchResults = $searchResults;
					// pagination
					$pageLinks = array();
					$pageCount = round($overAllCount/$resultsPerPage, PHP_ROUND_HALF_UP);
					
					if($pageOffset>0){
						$pageLinks[] = array("data-page"=>$pageOffset,"label"=>"Previous","isCurrent"=>false);
					}
					
					$paginationRange = 10;
					$startRange = $pageOffset-intval($paginationRange/2);
					$endRange = $pageOffset+intval($paginationRange/2);
					
					for($i=$startRange;$i<$endRange;$i++){
						if($i>=0 && $i<$pageCount){
							$pageLinks[]= array("data-page"=>($i+1),"label"=>($i+1),"isCurrent"=>($pageOffset==$i));
						}else if($i<$pageOffset) $endRange++;
					}
					
					if($pageOffset<$pageCount-1){
						$pageLinks[] = array("data-page"=>($pageOffset+2),"label"=>"Next","isCurrent"=>false);
					}
					
					$this->template->pageLinks = $pageLinks;
				}else{
					$this->template->message = "No results were Found.";
				}
				
				// async template rendring
				if(!$this->template->preRequest){
					$this->template->load("results", "search");
					$this->template->render();
				}
			}
		}
	}

}
?>
