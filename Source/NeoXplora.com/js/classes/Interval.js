var TInterval_Implementation = {
  
  construct: function(from, until) {
    this.base(this);
    if(from > until) {
      this.setFrom(until);
      this.setUntil(from);
    } else {
    	this.setFrom(from);
      this.setUntil(until);
    }
  },
  
  properties: {
    From: -1,
    Until: -1
  },
  
  methods: {
  	
  	Adjacent: function(AnInterval){
  		return (this.Until == AnInterval.From) || (AnInterval.Until == this.From);
  	},
 	
  	OverlapsWith: function(AnInterval) {
  	  return (this.Until > AnInterval.From) && (AnInterval.Until > this.From);
  	}
  }

};

Sky.Class.Define("Sky.TInterval", TInterval_Implementation);