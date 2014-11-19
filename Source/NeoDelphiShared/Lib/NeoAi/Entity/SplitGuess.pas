unit SplitGuess;

interface

uses
  Entity, EntityList, TypesConsts, SkyLists;

type
  TSplitGuess = class(TEntity)
  private
    FSplitStatus: Boolean;
    FSplits: TEntityList;
    FSentence: string;
    FMatchScore: Double;
    FMatchId: TId;
    FMatchText: string;
    FSubstitutions: TSkyStringStringList;
  published
    property Sentence: string read FSentence write FSentence;

    property MatchText: string read FMatchText write FMatchText;
    property MatchId: TId read FMatchId write FMatchId;
    property MatchScore: Double read FMatchScore write FMatchScore;
    property SplitStatus: Boolean read FSplitStatus write FSplitStatus;
    property Splits: TEntityList read FSplits write FSplits;
    property Substitutions: TSkyStringStringList read FSubstitutions write FSubstitutions;
  end;

implementation

end.