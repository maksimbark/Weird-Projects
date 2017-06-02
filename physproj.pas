program phys;

uses crt, graphabc, abcbuttons, system.threading;

var
  x, y: array[1..100] of real;
  smX, smY: array[1..100] of real;
  ignore: array[1..100] of integer;
  rad: integer;
  n: integer;
  lose: real;
  flag2: boolean;


procedure velupdate;
var
  i: integer;
begin
  clrscr;
  setconsoleio;
  for i := 1 to n do 
  begin
   textout(0, 380 + 20 * i, '                                                                                                                                    ');
    textout(0, 380 + 20 * i, 'Модуль скорости ' + i + '-ой точки = ' + trunc(sqrt(sqr(smX[i]) + sqr(smY[i])) * 100) / 100 + ' м/c');
    if (sqrt(sqr(smX[i]) + sqr(smY[i])) < 0.001) then begin
      smX[i] := 0;
      smY[i] := 0;
    end;
  end;
end;

procedure MouseDown(a, b, mousebutton: integer);
begin
  if (a < 340) and (a > 70) and (b < 340) and (b > 70) and (n < 10) then begin
    setBrushColor(clBlue);
    circle(a, b, rad);
    setBrushColor(clWhite);
    n += 1;
    randomize;
    x[n] := a;
    y[n] := b;
    smX[n] := random(3) + 1;
    smY[n] := random(3) + 1;
    velupdate;
  end;
  
  
end;

procedure go;
var
  i, j, k: integer;
  tempx, tempy: real;
  flag: boolean;
begin
  while (true) do 
  begin
    if (flag2) then begin
      
      for i := 1 to n do 
      begin
      lockdrawing;
        flag := false;
        if (ignore[i] > 0) then ignore[i] -= 1;
        for j := 1 to n do 
        begin
          if (i <> j) and (ignore[i] = 0) then begin
            if sqrt(sqr(x[i] - x[j]) + sqr(y[i] - y[j])) <= 2 * rad then begin
              //  tempx := smX[i] + smX[j];
              //  tempy := smY[i] + smY[j];
                //smX[i] := tempx/2;
                //smX[j] := tempx/2;
               // smY[i] := tempy/2;
              //  smY[j] := tempy/2;
              tempx := smX[i];
              smX[i] := smX[j];
              smX[j] := tempx;
              tempy := smY[i];
              smY[i] := smY[j];
              smY[j] := tempy;
              velupdate;
              flag := true;
            end;
          end;
        end;
        
        setPenWidth(2);
        setPenColor(clWhite);
        circle(round(x[i]), round(y[i]), rad);
        
        setPenColor(clBlack);
        
        // rectangle(50, 50, 350, 350);
        
        x[i] := x[i] + smX[i];
        y[i] := y[i] + smY[i];
        
        if (x[i] < 54 + 2 * rad) and (ignore[i] = 0) then begin smX[i] := -smX[i] * (1 - lose);smY[i] := smY[i] * (1 - lose);velupdate;flag := true; end;
        if (y[i] < 54 + 2 * rad) and (ignore[i] = 0) then begin smY[i] := -smY[i] * (1 - lose);smX[i] := smX[i] * (1 - lose);velupdate;flag := true; end;
        if (x[i] > 343 - 2 * rad) and (ignore[i] = 0) then begin smX[i] := -smX[i] * (1 - lose);smY[i] := smY[i] * (1 - lose);velupdate;flag := true; end;
        if (y[i] > 343 - 2 * rad) and (ignore[i] = 0) then begin smY[i] := -smY[i] * (1 - lose);smX[i] := smX[i] * (1 - lose);velupdate;flag := true; end;
        
        setPenWidth(1);
        setBrushColor(clBlue);
        circle(round(x[i]), round(y[i]), rad);
        setBrushColor(clWhite);
        if flag then ignore[i] := 2;
      end;
       roundrect(100,3,300,33,5,5);
     textout(185, 7, 'stop');
      redraw;
      sleep(3);
    end;
  end;
  
end;

procedure runAsync(proc: procedure);
begin
  var t := new Thread(proc);
  t.Start;
end;

procedure crutch;
begin
  if (not flag2) then begin
    setfontcolor(clgreen);
    textout(150, 351, 'Status: progress');
    setfontcolor(clblack);
  end else begin
    setfontcolor(clred);
    textout(150, 351, 'Status: pause                            ');
    setfontcolor(clblack);
  end;
  flag2 := not flag2;
end;

begin
  flag2 := false;
  //setconsoleio;
  setwindowsize(430, 600);
  writeln('Введите коэффициент потерь при ударе об стенку в формате 0.1: ');
  readln(lose);
  while((lose < 0.0) or (lose > 1.0)) do 
  begin
    writeln('Значение должно быть более 0.0 и менее 1.0, повторите ввод');
    readln(lose);
  end;
  clrscr;
  writeln('OK. Для добавления шарика надо нажать внутри квадратика.' + #13#10 + 'Максимальное количество 10.' + #13#10 + 'Для продолжения нажмите Enter');
  readln;
  clearwindow;
  
  OnMouseDown := MouseDown; 
  
  var moarButton := new ButtonABC(100, 3, 200, 30, 'start', clwhite);
  runAsync(go);
  moarButton.OnClick := (crutch);
  
  rad := 5;
  
  rectangle(50, 50, 350, 350);
  setfontcolor(clred);
  textout(150, 351, 'Status: pause');
  setfontcolor(clblack);
end.
