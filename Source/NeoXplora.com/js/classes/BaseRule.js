var TBaseRule_Implementation = {

	construct: function() {
		this.base(this);
	},
	
	properties: {
		ConjunctionType:  "ctAnd",
		Index: 0,
		Parent: null,
		DBId : -1,
		Modified : true
	},
	
	methods: {
		CanMoveUp: function() {
			if (this.getIndex() == 0 && (this.getParent() == null || this.getParent().getParent() == null)) {
				return false;
			} else {
				return true;
			}
		},
		
		CanMoveDown: function() { // boolean
			if (this.getParent() == null) {
				return false;
			} else if (this.getIndex() == this.getParent().getChildren().length - 1 && this.getParent().getParent() == null) {
				return false;
			} else {
				return true;
			}
		},
		
		MoveUp: function() {
			this.getParent().MoveChild(this.getIndex(), true);
		},
		
		MoveDown: function() {
			this.getParent().MoveChild(this.getIndex(), false);
		},
		
		SetModified: function(value){
			if(value){
				this.setModified(true);
				var parent = this.getParent();
				while(parent!=null){
					parent.setModified(true);
					parent = parent.getParent();
				}
			}else{
				this.setModified(false);
			}	
		}
	}
}

Sky.Class.Define("NeoAI.TBaseRule", TBaseRule_Implementation);