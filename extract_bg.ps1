$content = Get-Content -Path 'd:\bpr\web\SIPADU\sipadu-app\resources\views\new_design.html' -Raw
if ($content -match '(?s)<!-- BEGIN: Background Visuals -->(.*?)<!-- END: Background Visuals -->') {
    Set-Content -Path 'd:\bpr\web\SIPADU\sipadu-app\resources\views\components\cyberpunk-bg.blade.php' -Value $matches[1]
}
