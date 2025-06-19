function drawEnergyChart() {
    const canvas = document.getElementById('energyChart');
    if (!canvas || !canvas.getContext) return;

    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;

    const ctx = canvas.getContext('2d');
    const padding = {
        left: 70,    
        right: 40,
        top: 50,     
        bottom: 60  
    };

    const width = canvas.width - padding.left - padding.right;
    const height = canvas.height - padding.top - padding.bottom;

    // schoon maken
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    const chartData = energyData.slice(0, 20).reverse();
    const maxEnergy = 130;

    // teken achterground 
    ctx.strokeStyle = '#e0e0e0';
    ctx.beginPath();
    for (let i = 0; i <= 13; i++) {
        const y = padding.top + (height * i / 13);
        ctx.moveTo(padding.left, y);
        ctx.lineTo(canvas.width - padding.right, y);
    }
    ctx.stroke();

    // teken axes
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(padding.left, padding.top);
    ctx.lineTo(padding.left, canvas.height - padding.bottom);
    ctx.lineTo(canvas.width - padding.right, canvas.height - padding.bottom);
    ctx.stroke();

    // teken bars
    const barWidth = width / (chartData.length * 2.5); // Reduced to 2.5 for more spacing
    chartData.forEach((data, index) => {
        // Total energy
        const x1 = padding.left + (index * barWidth * 2.5);
        const totalHeight = (data.total_energy_kwh / maxEnergy) * height;
        ctx.fillStyle = 'rgba(0, 123, 255, 0.7)';
        ctx.fillRect(
            x1,
            canvas.height - padding.bottom - totalHeight,
            barWidth,
            totalHeight
        );

        const x2 = x1 + barWidth;
        const peakHeight = (data.peak_usage_kwh / maxEnergy) * height;
        ctx.fillStyle = 'rgba(255, 0, 0, 0.7)';
        ctx.fillRect(
            x2,
            canvas.height - padding.bottom - peakHeight,
            barWidth,
            peakHeight
        );

        // dagen labels
        ctx.fillStyle = 'black';
        ctx.font = '12px Arial';
        ctx.textAlign = 'center';
        const date = new Date(data.usage_date).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric'
        });
        ctx.save();
        ctx.translate(x1 + barWidth, canvas.height - padding.bottom + 10);
        ctx.rotate(-Math.PI / 4);
        ctx.fillText(date, 0, 0);
        ctx.restore();
    });

    // Y-axis 
    ctx.textAlign = 'right';
    ctx.font = '14px Arial';
    for (let i = 0; i <= 13; i++) {
        const value = i * 10;
        const y = canvas.height - padding.bottom - (height * i / 13);
        ctx.fillText(`${value} kWh`, padding.left - 10, y + 4);
    }

    const legendY = padding.top - 30;
    ctx.fillStyle = 'rgba(0, 123, 255, 0.7)';
    ctx.fillRect(padding.left, legendY, 20, 20);
    ctx.fillStyle = 'rgba(255, 0, 0, 0.7)';
    ctx.fillRect(padding.left + 150, legendY, 20, 20);
    ctx.fillStyle = 'black';
    ctx.textAlign = 'left';
    ctx.font = '14px Arial';
    ctx.fillText('Total Energy', padding.left + 30, legendY + 15);
    ctx.fillText('Peak Usage', padding.left + 180, legendY + 15);
}

window.addEventListener('load', drawEnergyChart);
