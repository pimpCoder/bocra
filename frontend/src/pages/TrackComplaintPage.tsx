import { Link, useLocation, useNavigate } from 'react-router-dom'
import FeedbackModal from '../components/complaints/FeedbackModal'
import TrackComplaintSection from '../components/complaints/TrackComplaintSection'
import type { ComplaintRecord } from '../types/complaints'
import { useState } from 'react'

type TrackLocationState = {
  trackingId?: string
}

function TrackComplaintPage() {
  const navigate = useNavigate()
  const location = useLocation()
  const [feedbackModal, setFeedbackModal] = useState<{
    isOpen: boolean
    title: string
    message: string
    tone: 'success' | 'error'
  }>({
    isOpen: false,
    title: '',
    message: '',
    tone: 'success',
  })
  const state = (location.state as TrackLocationState | null) ?? null
  const initialTrackingId = state?.trackingId ?? window.localStorage.getItem('bocra_last_tracking_id') ?? ''

  const handleFound = (record: ComplaintRecord) => {
    navigate(`/complaints/status/${encodeURIComponent(record.trackingId)}`)
  }

  return (
    <div className="py-8 md:py-10">
      <div className="mb-6 rounded-2xl border border-slate-200 bg-slate-50 p-5 md:p-6">
        <p className="text-sm text-slate-700 md:text-base">
          Need to file a new issue?{' '}
          <Link className="font-semibold text-blue-700 hover:text-blue-800" to="/complaints">
            Submit a complaint
          </Link>
          .
        </p>
      </div>

      <TrackComplaintSection
        initialTrackingId={initialTrackingId}
        onFound={handleFound}
        onInvalidId={() =>
          setFeedbackModal({
            isOpen: true,
            title: 'Invalid Tracking ID',
            message: 'Please enter a valid tracking ID before checking status.',
            tone: 'error',
          })
        }
        onNotFound={(trackingId) =>
          setFeedbackModal({
            isOpen: true,
            title: 'Complaint Not Found',
            message: `No complaint was found for tracking ID ${trackingId}. Please verify and try again.`,
            tone: 'error',
          })
        }
      />

      <FeedbackModal
        isOpen={feedbackModal.isOpen}
        onClose={() => setFeedbackModal((current) => ({ ...current, isOpen: false }))}
        title={feedbackModal.title}
        message={feedbackModal.message}
        tone={feedbackModal.tone}
      />
    </div>
  )
}

export default TrackComplaintPage
