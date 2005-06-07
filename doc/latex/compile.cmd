rem date >> protokoll.log
rem echo LaTeX Compiler startet >> protokoll.log
latex seawars.tex
makeindex seawars.idx
latex seawars.tex
dvips seawars.dvi
ps2pdf seawars.ps
rem zip -9 -j seawars.pdf.zip seawars.pdf && zip -9 -j seawars.ps.zip seawars.ps
echo DVI, PS, PDF erstellt >> protokoll.log
echo PS.zip, PDF.zip erzeugt >> protokoll.log
rem date >> protokoll.log
echo LaTeX Vorgang abgeschlossen >> protokoll.log
echo ============================================  >> protokoll.log
