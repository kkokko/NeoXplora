unit RepSerializeTest;

interface

uses
  TestRunner;

type
  TRepSerializeTest = class(TUnitTest)
  public
    procedure RegisterTestSteps; override;
  end;

implementation

uses
  TypesConsts, SysUtils, Interval, RepRecord, RepDecoder;

type
  TSerializeTest = class(TTestStep)
  protected
    procedure TestDecodeEncode(const AInputString, AnOutputString: string);
  end;

  TTestSimpleAttribute = class(TSerializeTest) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

  TTestSimpleEvent = class(TSerializeTest) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

  TTestAttributeWithNoValue = class(TSerializeTest) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

  TTestEventWithNoValue = class(TSerializeTest) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

  TTestSortedProperties = class(TSerializeTest) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

  TTestAttributeKeyWithChildren = class(TSerializeTest) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

  TTestAttributeValueWithChildren = class(TSerializeTest) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

  TTestPersonReferences = class(TSerializeTest) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

  TTestGroup = class(TSerializeTest) public
    procedure Run(AUnitTest: TUnitTest); override;
  end;

{ TRepSerializeTest }

procedure TRepSerializeTest.RegisterTestSteps;
begin
  Steps.Add(TTestSimpleAttribute.Create);
  Steps.Add(TTestSimpleEvent.Create);
  Steps.Add(TTestAttributeWithNoValue.Create);
  Steps.Add(TTestEventWithNoValue.Create);
  Steps.Add(TTestSortedProperties.Create);
  Steps.Add(TTestAttributeKeyWithChildren.Create);
  Steps.Add(TTestAttributeValueWithChildren.Create);
  Steps.Add(TTestPersonReferences.Create);
  Steps.Add(TTestGroup.Create);
end;

procedure TTestSimpleAttribute.Run(AUnitTest: TUnitTest);
begin
  TestDecodeEncode('p1 .name = John', 'p1.name = John');
  TestDecodeEncode('p1. name = John', 'p1.name = John');
  TestDecodeEncode('p1 . name = John', 'p1.name = John');
  TestDecodeEncode('p1.name = John', 'p1.name = John');
  TestDecodeEncode('p1.name= John', 'p1.name = John');
  TestDecodeEncode('p1.name =John', 'p1.name = John');
  TestDecodeEncode('p1.name = "John-Smith"', 'p1.name = "John-Smith"');
  TestDecodeEncode('p1.age = young, p1.age = 19, p1.age > 15', 'p1(.age = 19 + young, .age > 15)');
end;

procedure TTestSimpleEvent.Run(AUnitTest: TUnitTest);
begin
  TestDecodeEncode('p1:goes = home', 'p1:goes = home');
  TestDecodeEncode('p1:goes = "to Jane''s"', 'p1:goes = "to Jane''s"');
end;

procedure TTestAttributeWithNoValue.Run(AUnitTest: TUnitTest);
begin
  TestDecodeEncode('p1.name', 'p1.name');
end;

procedure TTestEventWithNoValue.Run(AUnitTest: TUnitTest);
begin
  TestDecodeEncode('p1:goes', 'p1:goes');
end;

procedure TTestSortedProperties.Run(AUnitTest: TUnitTest);
begin
  TestDecodeEncode('p1.name = John,p1:goes = home', 'p1(:goes = home, .name = John)');
end;

procedure TTestAttributeKeyWithChildren.Run(AUnitTest: TUnitTest);
begin
  TestDecodeEncode('p1.house(:looks = tall, .color = green) = nice', 'p1.house(.color = green, :looks = tall) = nice');
  TestDecodeEncode('p1.house(:looks = tall) = nice, p1.house(.color = green) = cool', 'p1.house(.color = green, :looks = tall) = cool + nice');
end;

procedure TTestAttributeValueWithChildren.Run(AUnitTest: TUnitTest);
begin
  TestDecodeEncode('p1.house = nice(:looks = tall, .color = green)', 'p1.house = nice(.color = green, :looks = tall)');
  TestDecodeEncode('p1.house = nice(:looks = tall), p1.house = cool(.color = green)', 'p1.house = cool(.color = green) + nice(:looks = tall)');
end;

procedure TTestPersonReferences.Run(AUnitTest: TUnitTest);
begin
  TestDecodeEncode('p1.name = John, p1.friend = [p2], p2.name = Smith', 'p1(.friend = [p2], .name = John), p2.name = Smith');
end;

procedure TTestGroup.Run(AUnitTest: TUnitTest);
begin
  TestDecodeEncode('g1([p1] + [p2], .ref = they), g1.name = the band', 'g1([p1] + [p2], .name = the band, .ref = they)');
end;

{ TSerializeTest }

procedure TSerializeTest.TestDecodeEncode(const AInputString, AnOutputString: string);
var
  TheRepRecord: TRepRecord;
begin
  TheRepRecord := TRepDecoder.DecodeRep(AInputString);
  try
    Test(TheRepRecord.GetAsString = AnOutputString, AnOutputString);
  finally
    TheRepRecord.Free;
  end;
end;

initialization
  TRepSerializeTest.RegisterTest;

end.
