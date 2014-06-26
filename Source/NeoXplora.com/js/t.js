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

function class2() {
	var obj = {};
	obj = class1();
	
	obj.m2 = function(){
	  this.test = '55';
	};

	return obj;
}

var newObj = class2();

console.log(newObj);

newObj.m1();

console.log(newObj);