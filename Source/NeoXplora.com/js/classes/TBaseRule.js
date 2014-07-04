

var TBaseRule =function(){
  //properties
    this.ConjunctionType = "ctAnd"; // defaults to ctAnd //(valid values: ctAnd / ctOr)
    this.Index = 0;  // integer ( default : 0)
    this.Parent = null; //(TRuleGroup)
  //methods
    this.CanMoveUp = function(){ // boolean
		if (this.Index == 0 && (this.Parent == null || this.Parent.Parent == null)){
			return false;
		} else {
			return true;
		}
	}
	
    this.CanMoveDown = function(){ // boolean
		if (this.Parent == null){
			return false;
		} else if( this.Index==this.Parent.Children.length-1 && this.Parent.Parent == null){
			return false;
		}else{
			return true;
		}
	}
	
    this.MoveUp = function(){
		this.Parent.MoveChild(this.Index,true);
	}
	
    this.MoveDown = function(){
		this.Parent.MoveChild(this.Index,false);
	}
	
}