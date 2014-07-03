function class1() {
	var obj = {};
	obj.test = "22";
	obj.m1 = function(){
	  this.m2();
	  this.test = this.test + '44';
	};
	obj.m2 = function(){
	  this.test = "33";
	};	
	return obj;
}

var newObj = class1.apply(this);

console.log(newObj);
