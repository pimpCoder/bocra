import type { ComplaintFormValues, ComplaintRecord, ComplaintStatus } from '../types/complaints'

const STORAGE_KEY = 'bocra_complaints'

const statusMessages: Record<ComplaintStatus, string> = {
  Submitted: 'Your complaint has been logged and is awaiting assignment.',
  'In Review': 'Our team is reviewing your complaint and may contact you for more details.',
  Resolved: 'Your complaint has been resolved. Thank you for your patience.',
}

const seededComplaints: ComplaintRecord[] = [
  {
    trackingId: 'BOCRA-20260320-1387',
    name: 'Kagiso M.',
    email: 'kagiso@example.com',
    company: 'Kgetsi Telecoms',
    phone: '+267 71 000 111',
    category: 'Internet Speed',
    complaint: 'Average speeds are significantly lower than advertised during peak hours.',
    submittedAt: '2026-03-20T08:30:00.000Z',
    status: 'In Review',
    statusMessage: statusMessages['In Review'],
  },
  {
    trackingId: 'BOCRA-20260318-9021',
    name: 'Neo P.',
    email: 'neo@example.com',
    company: 'Village Media',
    phone: '+267 72 222 333',
    category: 'Billing',
    complaint: 'Recurring charges are being applied after cancellation request.',
    submittedAt: '2026-03-18T11:15:00.000Z',
    status: 'Resolved',
    statusMessage: statusMessages.Resolved,
  },
]

const hasWindow = () => typeof window !== 'undefined'

const isComplaintRecord = (value: unknown): value is ComplaintRecord => {
  if (!value || typeof value !== 'object') return false
  const entry = value as Record<string, unknown>
  return (
    typeof entry.trackingId === 'string' &&
    typeof entry.name === 'string' &&
    typeof entry.email === 'string' &&
    typeof entry.company === 'string' &&
    typeof entry.phone === 'string' &&
    typeof entry.category === 'string' &&
    typeof entry.complaint === 'string' &&
    typeof entry.submittedAt === 'string' &&
    typeof entry.status === 'string' &&
    typeof entry.statusMessage === 'string'
  )
}

const readComplaints = (): ComplaintRecord[] => {
  if (!hasWindow()) return []
  const raw = window.localStorage.getItem(STORAGE_KEY)
  if (!raw) return []

  try {
    const parsed = JSON.parse(raw)
    if (!Array.isArray(parsed)) return []
    return parsed.filter(isComplaintRecord)
  } catch {
    return []
  }
}

const writeComplaints = (complaints: ComplaintRecord[]) => {
  if (!hasWindow()) return
  window.localStorage.setItem(STORAGE_KEY, JSON.stringify(complaints))
}

export const ensureComplaintSeedData = () => {
  const current = readComplaints()
  if (current.length > 0) return
  // Seed demo records so tracking/status screens are meaningful on first load.
  writeComplaints(seededComplaints)
}

const randomSuffix = () => Math.floor(1000 + Math.random() * 9000).toString()

const buildTrackingId = () => {
  const now = new Date()
  const yyyy = now.getFullYear()
  const mm = String(now.getMonth() + 1).padStart(2, '0')
  const dd = String(now.getDate()).padStart(2, '0')
  return `BOCRA-${yyyy}${mm}${dd}-${randomSuffix()}`
}

const uniqueTrackingId = (existing: ComplaintRecord[]) => {
  let candidate = buildTrackingId()
  while (existing.some((item) => item.trackingId === candidate)) {
    candidate = buildTrackingId()
  }
  return candidate
}

export const submitComplaint = (values: ComplaintFormValues): ComplaintRecord => {
  ensureComplaintSeedData()
  const complaints = readComplaints()

  const record: ComplaintRecord = {
    ...values,
    trackingId: uniqueTrackingId(complaints),
    submittedAt: new Date().toISOString(),
    status: 'Submitted',
    statusMessage: statusMessages.Submitted,
  }

  writeComplaints([record, ...complaints])
  return record
}

export const getComplaintByTrackingId = (trackingId: string): ComplaintRecord | null => {
  ensureComplaintSeedData()
  const complaints = readComplaints()
  const normalized = trackingId.trim().toUpperCase()
  return (
    complaints.find((entry) => entry.trackingId.trim().toUpperCase() === normalized) ?? null
  )
}
