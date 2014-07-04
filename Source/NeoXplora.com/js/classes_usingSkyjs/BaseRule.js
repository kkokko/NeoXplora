var TBaseRule_Implementation = {

	construct: function() {
		this.base(this);
	},
	
	properties: {
		ConjunctionType:  "ctAnd",
		Index: 0,
		Parent: null
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
		}
	}
}

Sky.Class.Define("NeoAI.TBaseRule", TBaseRule_Implementation);