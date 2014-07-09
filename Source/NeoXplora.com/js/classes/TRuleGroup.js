

var TRuleGroup = function() { 
	
	TBaseRule.apply(this, arguments);// extends TBaseRule
  //properties
    this.Children = [];  //(array of RuleValue/RuleGroup)
  //methods
    this.InsertChild = function(child, index){ // (TBaseRule, integer)
		index = typeof index !== 'undefined' ? index : -1;
		if(child!=null){
			child.Parent = this;
			if(index<0){
				child.Index = this.Children.length;
				this.Children.push(child);
			}else{
				child.Index = index;
				this.Children.splice(index, 0, child);
				this.reAdjustIndexes();
			}
		}
	}
	
    this.MoveChild = function(childIndex,upDownToggle){  //(integer, boolean)
		if(upDownToggle){ // true = UP
			if(childIndex==0){
				var child = this.Children[childIndex];
				this.RemoveChild(childIndex);
				this.Parent.InsertChild(child,this.Index);
				if(this.Children.length==0) this.Parent.RemoveChild(this.Index);
			}else if (!this.Children[childIndex-1].hasOwnProperty('Children')){
				this.swapChildren(childIndex,childIndex-1);
			}else{
				var child = this.Children[childIndex];
				this.RemoveChild(childIndex);
				this.Children[childIndex-1].InsertChild(child);
			}
		}else{ // false = DOWN
			if(childIndex==this.Children.length-1){
				var child = this.Children[childIndex];
				this.RemoveChild(childIndex);
				this.Parent.InsertChild(child,this.Index+1);
				if(this.Children.length==0) this.Parent.RemoveChild(this.Index);
			}else if (!this.Children[childIndex+1].hasOwnProperty('Children')){
				this.swapChildren(childIndex,childIndex+1);
			}else{
				var child = this.Children[childIndex];
				this.RemoveChild(childIndex);
				this.Children[childIndex].InsertChild(child,0);
			}
		}
	}
    this.RemoveChild = function (childIndex){  //(integer)
		if(childIndex<this.Children.length){
			this.Children.splice(childIndex, 1);
			this.reAdjustIndexes();
		} else throw "IndexOutOfReachException";
	}
	
	this.reAdjustIndexes = function(){
		for(var i=0;i<this.Children.length;i++){
			this.Children[i].Index = i;
		}
	}
	
	this.swapChildren =  function (index1,index2){
		var tmp = this.Children[index1];
		this.Children[index1] = this.Children[index2];
		this.Children[index2] = tmp;
		var tmpIndex = this.Children[index1].Index;
		this.Children[index1].Index = this.Children[index2].Index;
		this.Children[index2].Index = tmpIndex;
	}
	
	this.toString = function(indent){
		indent = typeof indent !== 'undefined' ? indent : 0;
		var indentStr = "";
		while(indent>0){indentStr+="\t";indent--;}
		var result = indentStr+"TRuleGroup["+this.Index+"]:{ ConjunctionType: "+this.ConjunctionType;
		
		var resultChildren = "";
		for(var i=0;i<this.Children.length;i++){
			resultChildren += indentStr + this.Children[i].toString(indent+1)+"\n";
		}
		
		if(resultChildren!=""){
			result += ",\n"+indentStr+"\tChildren:"+indentStr+"\t\n"+resultChildren;
		}
		result += indentStr+"}\n";
		return result;
	}
	
}