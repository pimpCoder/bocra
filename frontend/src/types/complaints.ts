export const complaintCategories = [
  'Research',
  'Licencing',
  'Policy and Regulation',
  'Standards',
  'Numbering',
  'ccTLD',
  'Radio Interference',
  'Billing',
  'Internet Speed',
  'Quality of Service',
  'Broadcasting',
] as const

export type ComplaintCategory = (typeof complaintCategories)[number]

export type ComplaintStatus = 'Submitted' | 'In Review' | 'Resolved'

export type ComplaintRecord = {
  trackingId: string
  name: string
  email: string
  company: string
  phone: string
  category: ComplaintCategory
  complaint: string
  submittedAt: string
  status: ComplaintStatus
  statusMessage: string
}

export type ComplaintFormValues = Omit<
  ComplaintRecord,
  'trackingId' | 'submittedAt' | 'status' | 'statusMessage'
>
