<?php // app/Views/student/dashboard.php ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap');

* { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --accent:   #1a56db;
    --success:  #0d9488;
    --warn:     #d97706;
    --info:     #7c3aed;
    --surface:  var(--color-background-primary);
    --surface2: var(--color-background-secondary);
    --border:   var(--color-border-tertiary);
    --text:     var(--color-text-primary);
    --muted:    var(--color-text-secondary);
    --hint:     var(--color-text-tertiary);
    --r:        14px;
}

/* Layout */
.dash {
    font-family: 'DM Sans', sans-serif;
    padding: 2rem 1.5rem 3rem;
    max-width: 960px;
    margin: 0 auto;
}

/* Greeting */
.greeting            { margin-bottom: 2rem; }
.greeting-eyebrow    { font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: var(--hint); font-weight: 500; margin-bottom: .3rem; }
.greeting-name       { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 700; color: var(--text); line-height: 1.1; }
.greeting-name span  { color: var(--accent); }
.id-badge            { display: inline-flex; align-items: center; gap: 6px; background: var(--surface2); border: 0.5px solid var(--border); border-radius: 6px; padding: 3px 10px; font-size: 12px; color: var(--muted); font-weight: 500; letter-spacing: .04em; }
.id-dot              { width: 6px; height: 6px; border-radius: 50%; background: #0d9488; display: inline-block; }

/* Section label */
.section-label { font-size: 11px; letter-spacing: .1em; text-transform: uppercase; color: var(--hint); font-weight: 500; margin-bottom: 1rem; }

/* Action cards */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
    margin-bottom: 2rem;
}
.card {
    background: var(--surface);
    border: 0.5px solid var(--border);
    border-radius: var(--r);
    padding: 1.25rem 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    gap: .75rem;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: border-color .18s, transform .15s;
    text-decoration: none;
}
.card:hover      { border-color: var(--color-border-secondary); transform: translateY(-1px); }
.card-accent     { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: var(--r) var(--r) 0 0; }
.card-icon       { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.card-icon svg   { width: 18px; height: 18px; }
.card-title      { font-family: 'Syne', sans-serif; font-size: .95rem; font-weight: 700; color: var(--text); line-height: 1.2; }
.card-desc       { font-size: 12.5px; color: var(--muted); line-height: 1.5; flex: 1; }
.card-cta        { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 500; border-radius: 7px; padding: 6px 12px; border: none; cursor: pointer; transition: opacity .15s; text-decoration: none; margin-top: auto; width: fit-content; }
.card-cta svg    { width: 12px; height: 12px; }

/* Card color variants */
.c-blue .card-accent   { background: linear-gradient(90deg, #1a56db, #60a5fa); }
.c-blue .card-icon     { background: #dbeafe; }
.c-blue .card-icon svg { color: #1a56db; }
.c-blue .card-cta      { background: #1a56db; color: #fff; }

.c-green .card-accent   { background: linear-gradient(90deg, #0d9488, #34d399); }
.c-green .card-icon     { background: #f0fdf9; }
.c-green .card-icon svg { color: #0d9488; }
.c-green .card-cta      { background: #0d9488; color: #fff; }

.c-purple .card-accent   { background: linear-gradient(90deg, #7c3aed, #a78bfa); }
.c-purple .card-icon     { background: #f5f3ff; }
.c-purple .card-icon svg { color: #7c3aed; }
.c-purple .card-cta      { background: #7c3aed; color: #fff; }

.c-amber .card-accent   { background: linear-gradient(90deg, #d97706, #fbbf24); }
.c-amber .card-icon     { background: #fffbeb; }
.c-amber .card-icon svg { color: #d97706; }
.c-amber .card-cta      { background: #d97706; color: #fff; }

/* Clearance progress */
.status-block  { background: var(--surface2); border: 0.5px solid var(--border); border-radius: var(--r); padding: 1.25rem; }
.status-row    { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: .5rem; }
.status-title  { font-size: .875rem; font-weight: 500; color: var(--text); }
.status-badge  { font-size: 11px; padding: 3px 9px; border-radius: 20px; font-weight: 500; }
.status-badge.status-in_progress { background: #dbeafe; color: #1e40af; }
.status-badge.status-approved { background: #d1fae5; color: #065f46; }
.status-badge.status-rejected { background: #fee2e2; color: #dc2626; }
.status-badge.status-none { background: #f3f4f6; color: var(--hint); }

.steps         { display: flex; gap: 6px; align-items: center; }
.step          { display: flex; flex-direction: column; align-items: center; gap: 4px; flex: 1; }
.step-dot      { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 500; }
.step-done     { background: #d1fae5; color: #065f46; }
.step-active   { background: #dbeafe; color: #1e40af; border: 2px solid #1a56db; }
.step-pending  { background: var(--surface); border: 0.5px solid var(--border); color: var(--hint); }
.step-label    { font-size: 10px; color: var(--muted); text-align: center; line-height: 1.3; }
.step-bar      { flex: 1; height: 2px; background: var(--border); border-radius: 2px; margin-bottom: 14px; }
.step-bar.done { background: #34d399; }

/* Notifications */
.notif-list     { display: flex; flex-direction: column; gap: 10px; margin-top: .5rem; }
.notif-item     { display: flex; align-items: flex-start; gap: 10px; padding: 10px 12px; background: var(--surface); border: 0.5px solid var(--border); border-radius: 10px; }
.notif-dot      { width: 8px; height: 8px; border-radius: 50%; margin-top: 4px; flex-shrink: 0; }
.notif-dot.new  { background: #1a56db; }
.notif-dot.done { background: #0d9488; }
.notif-dot.warn { background: #d97706; }
.notif-content  { flex: 1; }
.notif-title    { font-size: 13px; font-weight: 500; color: var(--text); }
.notif-time     { font-size: 11px; color: var(--hint); margin-top: 2px; }

.divider { border: none; border-top: 0.5px solid var(--border); margin: 1.5rem 0; }
</style>

<div class="dash">

    <!-- Greeting -->
    <div class="greeting">
        <div class="greeting-eyebrow">Student portal</div>
        <div class="greeting-name">Welcome back, <span><?= esc(session('fullName')) ?></span></div>
        <div style="display:flex; align-items:center; gap:10px; margin-top:.6rem; flex-wrap:wrap;">
            <div class="id-badge">
                <span class="id-dot"></span>
                <?= esc(session('studentId')) ?>
            </div>
            <span style="font-size:12px; color:var(--hint);">AY 2024–2025 &middot; 2nd Semester</span>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-label">Quick actions</div>
    <div class="cards">

        <a href="<?= site_url('student/clearance') ?>" class="card c-blue">
            <div class="card-accent"></div>
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
            </div>
            <div class="card-title">New clearance request</div>
            <div class="card-desc">Submit a new clearance for this semester's requirements.</div>
            <span class="card-cta">
                Start now
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </span>
        </a>

        <a href="<?= site_url('student/clearance/track') ?>" class="card c-green">
            <div class="card-accent"></div>
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>
            </div>
            <div class="card-title">Track progress</div>
            <div class="card-desc">Check approval status across all departments.</div>
            <span class="card-cta">
                View status
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </span>
        </a>

        <a href="<?= site_url('student/notifications') ?>" class="card c-purple">
            <div class="card-accent"></div>
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
            </div>
            <div class="card-title">Notifications</div>
            <div class="card-desc">You have updates from the registrar and advisors.</div>
            <span class="card-cta">
                View all
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </span>
        </a>

        <a href="<?= site_url('student/profile') ?>" class="card c-amber">
            <div class="card-accent"></div>
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div class="card-title">Profile settings</div>
            <div class="card-desc">Update your contact info and academic details.</div>
            <span class="card-cta">
                Update info
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </span>
        </a>

    </div>

    <!-- Clearance Progress -->
    <div class="section-label">Clearance progress</div>
    <div class="status-block" id="clearance-status-block">
        <?php if ($clearanceData['hasRequest']): ?>
        <div class="status-row">
            <span class="status-title">Current clearance — <?= esc($clearanceData['request']['semester'] ?? '2nd Sem 2024–25') ?></span>
            <span class="status-badge status-<?= esc($clearanceData['overallStatus']) ?>"><?= ucfirst(esc($clearanceData['overallStatus'])) ?></span>
        </div>
        <div class="steps" id="progress-steps">
            <?php 
            $stepKeys = array_keys($clearanceData['stepOrder']);
            foreach ($stepKeys as $index => $label): 
                $status = $clearanceData['stepOrder'][$label];
                $isActive = ($index == $clearanceData['activeIndex']);
                $showNumber = ($status != 'done');
                $barDone = ($index > 0 && $clearanceData['stepOrder'][$stepKeys[$index-1]] == 'done') || $status == 'done';
            ?>
            <div class="step">
                <div class="step-dot <?= 'step-' . $status ?> <?= $isActive ? 'step-active' : '' ?>">
                    <?php if ($status === 'done'): ?>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    <?php else: ?>
                    <?= $index + 1 ?>
                    <?php endif; ?>
                </div>
                <div class="step-label"><?= esc($label) ?></div>
            </div>
            <div class="step-bar <?= $barDone ? 'done' : '' ?>"></div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="padding: 1.5rem; text-align: center; color: var(--muted);">
            <p>No active clearance request. <a href="<?= site_url('student/clearance') ?>">Start one now</a></p>
        </div>
        <?php endif; ?>
    </div>

    <hr class="divider">

    <!-- Recent Notifications -->
    <div class="section-label">Recent notifications</div>
    <div class="notif-list" id="recent-notifications">
        <?php if (empty($recentNotifications)): ?>
            <div class="notif-item" style="justify-content: center; color: var(--muted); padding: 2rem;">
                <h5>No notifications yet</h5>
            </div>
        <?php else: ?>
            <?php foreach ($recentNotifications as $notif): 
                $type = $notif['type'] ?? 'info';
                $dotClass = match($type) {
                    'success','done' => 'done',
                    'danger','error','warn' => 'warn',
                    default => 'new'
                };
                $timeStr = $notif['created_at'] ? date('M j \\a t g:i A', strtotime($notif['created_at'])) : 'Just now';
            ?>
            <div class="notif-item">
                <div class="notif-dot <?= $dotClass ?>"></div>
                <div class="notif-content">
                    <div class="notif-title"><?= esc($notif['title']) ?></div>
                    <div class="notif-time"><?= esc($timeStr) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<script>
/* Real-time clearance updates */
const API_URL = '<?= site_url('api/v1/progress') ?>';

async function updateClearanceProgress() {
    try {
        const res = await fetch(API_URL);
        const data = await res.json();
        
        if (data.status !== 200) return;
        
        const statusBlock = document.getElementById('clearance-status-block');
        if (!data.hasRequest) {
            statusBlock.innerHTML = `<div style="padding: 1.5rem; text-align: center; color: var(--muted);">
                <p>No active clearance request. <a href="<?= site_url('student/clearance') ?>">Start one now</a></p>
            </div>`;
            updateNotifications([]);
            return;
        }
        
        // Update status badge/title
        document.querySelector('.status-title').textContent = `Current clearance — ${data.request.semester || '2nd Sem 2024–25'}`;
        const badge = document.querySelector('.status-badge');
        badge.textContent = data.steps.overallStatus.charAt(0).toUpperCase() + data.steps.overallStatus.slice(1);
        badge.className = `status-badge status-${data.steps.overallStatus}`;
        
        // Rebuild steps
        const stepsContainer = document.getElementById('progress-steps');
        let stepsHtml = '';
        const stepKeys = Object.keys(data.steps.order);
        stepKeys.forEach((label, i) => {
            const status = data.steps.order[label];
            const isActive = i === data.steps.activeIndex;
            const showNumber = status !== 'done';
            const barDone = i > 0 ? data.steps.order[stepKeys[i-1]] === 'done' : false;
            
            stepsHtml += `
                <div class="step">
                    <div class="step-dot step-${status}${isActive ? ' step-active' : ''}">
                        ${status === 'done' ? 
                            '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>' : 
                            i + 1
                        }
                    </div>
                    <div class="step-label">${label}</div>
                </div>
                <div class="step-bar ${barDone || status === 'done' ? 'done' : ''}"></div>
            `;
        });
        stepsContainer.innerHTML = stepsHtml;
        
        // Update notifications
        updateNotifications(data.notifications);
        
    } catch (e) {
        console.error('Update failed:', e);
    }
}

function updateNotifications(notifs) {
    const container = document.getElementById('recent-notifications');
    if (!notifs || notifs.length === 0) {
        container.innerHTML = `<div class="notif-item" style="justify-content: center; color: var(--muted); padding: 2rem;">
            <h5>No notifications yet</h5>
        </div>`;
        return;
    }
    
    let html = '';
    notifs.forEach(notif => {
        const dotClass = notif.type === 'success' || notif.type === 'done' ? 'done' : 
                        (['danger','error','warn'].includes(notif.type) ? 'warn' : 'new');
        html += `
            <div class="notif-item">
                <div class="notif-dot ${dotClass}"></div>
                <div class="notif-content">
                    <div class="notif-title">${notif.title}</div>
                    <div class="notif-time">${new Date(notif.created_at).toLocaleString()}</div>
                </div>
            </div>`;
    });
    container.innerHTML = html;
}

// Poll every 10s, initial after 1s
setTimeout(updateClearanceProgress, 1000);
setInterval(updateClearanceProgress, 10000);
</script>

<?= $this->endSection() ?>
